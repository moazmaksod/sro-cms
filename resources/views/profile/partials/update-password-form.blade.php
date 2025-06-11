<div class="card">
    <div class="card-header">{{ __('Update Password') }}</div>

    <div class="card-body">
        <div class="mb-3">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </div>
        <form id="send-verify-code-password" class="d-none" method="post" action="{{ route('profile.resend.verify.code') }}">
            @csrf
            <input type="hidden" name="send-verify-code-name" value="send-verify-code-password">
        </form>
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @method('put')

            @if(config('settings.update_type') !== 'verify_code')
                <div class="row mb-3">
                    <label for="password" class="col-md-4 col-form-label text-md-end">
                        {{ __('Current Password') }}
                    </label>

                    <div class="col-md-6">
                        <input id="current_password" type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" name="current_password" required autocomplete="current-password">

                        @error('current_password', 'updatePassword')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
            @else
                <div class="row mb-3">
                    <label for="verify_code_password" class="col-md-4 col-form-label text-md-end">
                        {{ __('Verification Code') }}
                    </label>

                    <div class="col-md-6">
                        <input id="verify_code_password" type="text" class="form-control @error('verify_code_password') is-invalid @enderror" name="verify_code_password" value="{{ old('verify_code_password', $user->verify_code_password) }}" required>

                        @error('verify_code_password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror

                        <div class="mt-2">
                            <p class="mb-0">
                                <button form="send-verify-code-password" class="btn btn-link p-0">
                                    {{ __('Send Verification code') }}
                                </button>
                            </p>

                            @if (session('status') === 'resend-verify-code-password')
                                <div class="alert alert-success mt-2">Verification Code sent to your current email.</div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <div class="row mb-3">
                <label for="password" class="col-md-4 col-form-label text-md-end">
                    {{ __('New Password') }}
                </label>

                <div class="col-md-6">
                    <input id="password" type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" name="password" required autocomplete="new-password">

                    @error('password', 'updatePassword')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="password_confirmation" class="col-md-4 col-form-label text-md-end">
                    {{ __('Confirm Password') }}
                </label>

                <div class="col-md-6">
                    <input id="password_confirmation" type="password" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" name="password_confirmation" required>

                    @error('password_confirmation', 'updatePassword')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Save') }}
                    </button>
                    @if (session('status') === 'password-updated')
                        <span class="m-1 fade-out">{{ __('Saved.') }}</span>
                    @endif
                </div>
            </div>
        </form>
    </div>
</div>
