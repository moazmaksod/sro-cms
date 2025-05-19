<?php

namespace App\Http\Requests;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $newEmailRules = ['required', 'email'];

        if (config('settings.duplicate_email', 1) == 0) {
            $newEmailRules[] = Rule::unique(User::class)->ignore($this->user()->id);
        }

        return [
            'name' => ['string', 'max:255'],
            'code' => ['required', 'string'],
            'new_email' => $newEmailRules,
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),

                function ($attribute, $value, $fail) {
                    if (config('global.server.version') === 'vSRO' && config('settings.duplicate_email', 1) == 0) {
                        $exists = DB::connection('account')->table('dbo.TB_User')
                            ->where('Email', $value)
                            ->where('JID', '!=', $this->user()->jid)
                            ->exists();

                        if ($exists) {
                            $fail('The email has already been taken in another account.');
                        }
                    }
                },
            ],
        ];
    }
}
