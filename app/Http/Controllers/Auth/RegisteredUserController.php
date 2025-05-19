<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SRO\Account\SkSilk;
use App\Models\SRO\Account\TbUser;
use App\Models\SRO\Portal\AphChangedSilk;
use App\Models\SRO\Portal\AuhAgreedService;
use App\Models\SRO\Portal\MuEmail;
use App\Models\SRO\Portal\MuhAlteredInfo;
use App\Models\SRO\Portal\MuJoiningInfo;
use App\Models\SRO\Portal\MuUser;
use App\Models\SRO\Portal\MuVIPInfo;
use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'username' => ['required', 'regex:/^[A-Za-z0-9]*$/', 'min:6', 'max:16', 'unique:' . User::class],
            'email' => ['required', 'string', 'email', 'max:70'],
            'password' => ['required', 'min:6', 'max:32', 'confirmed'],
            'g-recaptcha-response' => [Rule::requiredIf(fn () => env('NOCAPTCHA_ENABLE', false)), 'captcha'],
        ];

        if (config('global.server.version') === 'vSRO' && config('settings.duplicate_email', 1) === 0) {
            $rules['email'][] = 'unique:' . TbUser::class . ',Email';
        }

        if (config('global.server.version') === 'vSRO') {
            $rules['username'][] = 'unique:' . TbUser::class . ',StrUserID';
        } else {
            $rules['username'][] = 'unique:' . MuUser::class . ',UserID';
            $rules['username'][] = 'unique:' . TbUser::class . ',StrUserID';
            $rules['email'][] = 'unique:' . MuEmail::class . ',EmailAddr';
        }

        if (config('settings.agree_terms')) {
            $rules['terms'] = 'accepted';
        }

        $request->validate($rules);

        DB::beginTransaction();
        try {
            if (config('global.server.version') === 'vSRO') {
                $tbUser = TbUser::setGameAccount($jid = null, $request->username, $request->password, $request->email, $request->ip());
                $jid = $tbUser->JID;

                SkSilk::setSkSilk($jid, 0, 0);
            } else {
                //Fixing local registration
                $userBinIP = ($request->ip() == "::1") ? ip2long('127.0.0.1') : ip2long($request->ip());

                $portalUser = MuUser::setPortalAccount($request->username, $request->password);
                $jid = $portalUser->JID;

                MuEmail::setEmail($jid, $request->email);
                MuhAlteredInfo::setAlteredInfo($jid, $request->username, $request->email, $userBinIP);
                AuhAgreedService::setAgreedService($jid, $userBinIP);
                MuJoiningInfo::setJoiningInfo($jid, $userBinIP);
                MuVIPInfo::setVIPInfo($jid);

                //type 1 = silk, type 3 = premium silk
                //AphChangedSilk::setChangedSilk($jid, 1, 0);
                //AphChangedSilk::setChangedSilk($jid, 3, 0);
                TbUser::setGameAccount($jid, $request->username, $request->password, $request->email, $request->ip());
            }

            $user = User::create([
                'jid' => $jid,
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

        } catch (Exception $e) {
            DB::rollBack();
            return back()->withErrors(['username' => [$e->getMessage()]]);
        }
        DB::commit();

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('profile', absolute: false));
    }
}
