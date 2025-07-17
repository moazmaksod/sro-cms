<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\SRO\Account\SkSilk;
use App\Models\SRO\Account\TbUser;
use App\Models\SRO\Portal\AuhAgreedService;
use App\Models\SRO\Portal\MuEmail;
use App\Models\SRO\Portal\MuhAlteredInfo;
use App\Models\SRO\Portal\MuJoiningInfo;
use App\Models\SRO\Portal\MuUser;
use App\Models\SRO\Portal\MuVIPInfo;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $rules = [
            'username' => ['required', 'regex:/^[A-Za-z0-9]*$/', 'min:6', 'max:16', 'unique:' . User::class],
            'email' => ['required', 'string', 'email', 'max:70'],
            'password' => ['required', 'min:6', 'max:32', 'confirmed'],
            'invite' => ['nullable', 'string'],
            'fingerprint' => ['nullable', 'string'],
        ];

        if (config('global.server.version') === 'vSRO') {
            $rules['username'][] = 'unique:' . TbUser::class . ',StrUserID';
        } elseif (config('global.server.version') !== 'vSRO') {
            $rules['email'][] = 'unique:' . User::class . ',email';
            $rules['username'][] = 'unique:' . MuUser::class . ',UserID';
            $rules['username'][] = 'unique:' . TbUser::class . ',StrUserID';
            $rules['email'][] = 'unique:' . MuEmail::class . ',EmailAddr';
        }

        $request->validate($rules);

        DB::beginTransaction();
        try {
            if (config('global.server.version') === 'vSRO') {
                $tbUser = TbUser::setGameAccount($jid = null, $request->username, $request->password, $request->email, $request->ip());
                $jid = $tbUser->JID;

                SkSilk::setSkSilk($jid, 0, 0);
            } else {
                $userBinIP = ($request->ip() == "::1") ? ip2long('127.0.0.1') : ip2long($request->ip());

                $portalUser = MuUser::setPortalAccount($request->username, $request->password);
                $jid = $portalUser->JID;

                MuEmail::setEmail($jid, $request->email);
                MuhAlteredInfo::setAlteredInfo($jid, $request->username, $request->email, $userBinIP);
                AuhAgreedService::setAgreedService($jid, $userBinIP);
                MuJoiningInfo::setJoiningInfo($jid, $userBinIP);
                MuVIPInfo::setVIPInfo($jid);

                TbUser::setGameAccount($jid, $request->username, $request->password, $request->email, $request->ip());
            }

            if (config('global.referral.enabled', true) && $request->filled('invite')) {
                $invite = Referral::where('code', $request->invite)->first();

                if ($invite) {
                    Referral::create([
                        'code' => $invite->code,
                        'name' => $invite->name,
                        'jid' => $invite->jid,
                        'invited_jid' => $jid,
                        'points' => ($invite->ip !== $request->ip() && $invite->fingerprint !== $request->fingerprint)
                            ? config('global.referral.reward_points', 0)
                            : 0,
                        'ip' => ($invite->ip === $request->ip() || $invite->fingerprint === $request->fingerprint)
                            ? 'CHEATING'
                            : $request->ip(),
                    ]);
                }
            }

            $user = User::create([
                'jid' => $jid,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            DB::commit();

            if (config('settings.register_confirm')) {
                event(new Registered($user));
            }

            Auth::login($user);
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'message' => 'Registration successful',
                'user' => $user,
                'token' => $token,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($request->only('username', 'password'))) {
            $tbUser = TbUser::where('StrUserID', $request->username)->where('password', md5($request->password))->first();

            if (!$tbUser) {
                throw ValidationException::withMessages([
                    'username' => ['Invalid credentials.'],
                ]);
            }

            if (config('global.server.version') === 'vSRO') {
                $jid = $tbUser->JID;
                $email = $tbUser->Email ?? "{$jid}@mail.com";
            } else {
                $jid = $tbUser->PortalJID;
                $email = $tbUser->muUser->muEmail->EmailAddr ?? "{$jid}@mail.com";
            }

            $user = User::firstOrCreate(
                ['username' => $request->username],
                [
                    'jid' => $jid,
                    'email' => $email,
                    'password' => Hash::make($request->password),
                ]
            );

            Auth::login($user);
        }

        $token = $request->user()->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => $request->user(),
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully.'
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
