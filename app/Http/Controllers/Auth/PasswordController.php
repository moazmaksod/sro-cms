<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SRO\Account\TbUser;
use App\Models\SRO\Portal\MuhAlteredInfo;
use App\Models\SRO\Portal\MuUser;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => array_filter([
                !config('settings.update_type') == 'verify_code' ? 'required' : null,
                'current_password'
            ]),
            'password' => ['required', 'min:6', 'max:32', 'confirmed'],
            'verify_code_password' => array_filter([
                config('settings.update_type') == 'verify_code' ? 'required' : null,
                'string'
            ]),
        ]);

        if (config('settings.update_type') == 'verify_code') {
            $codeRecord = DB::table('password_reset_tokens')->where('email', $request->user()->email)->first();

            if (!$codeRecord || !($request->input('verify_code_password') === $codeRecord->token) || Carbon::parse($codeRecord->created_at)->addMinutes(30)->isPast()) {
                return back()->withErrors(['verify_code_password' => 'The provided verification code is invalid or expired.']);
            }
        }

        DB::beginTransaction();
        try {
            if (config('global.server.version') === 'vSRO') {
                TbUser::where('JID', $request->user()->jid)->update(['password' => md5($request->password)]);
            } else {
                MuUser::where('JID', $request->user()->jid)->update(['UserPwd' => md5($request->password)]);
                TbUser::where('PortalJID', $request->user()->jid)->update(['password' => md5($request->password)]);
            }

            if (config('settings.update_type') == 'verify_code') {
                DB::table('password_reset_tokens')->where('email', $request->user()->email)->delete();
            }

            DB::commit();

        } catch (Exception $e) {
            DB::rollBack();
        }

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
}
