<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
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
        return [
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),

                function ($attribute, $value, $fail) {
                    if (config('global.server.version') === 'vSRO' && config('settings.duplicate_email')) {
                        $exists = DB::connection('account')->table('TbUser')
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
