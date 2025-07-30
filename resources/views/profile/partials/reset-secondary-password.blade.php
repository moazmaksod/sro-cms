<div class="card">
    <div class="card-header">{{ __('Reset Secondry Password') }}</div>

    <div class="card-body">
        @if(session('passcode_success'))
            <div class="alert alert-success">{{ session('passcode_success') }}</div>
        @endif

        @if(session('passcode_error'))
            <div class="alert alert-danger">{{ session('passcode_error') }}</div>
        @endif

        <form id="send-verify-code-secondary" class="d-none" method="post" action="{{ route('profile.resend.verify.code') }}">
            @csrf
            <input type="hidden" name="send-verify-code-name" value="send-verify-code-password">
        </form>
        <form method="POST" action="{{ route('profile.reset.secondary.password') }}">
            @csrf

            @if(config('settings.update_type') !== 'verify_code')
                <div class="row mb-3">
                    <label for="password" class="col-md-4 col-form-label text-md-end">
                        {{ __('Password') }}
                    </label>

                    <div class="col-md-6">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>

                        @error('password')
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
                        <input id="verify_code_secondary" type="text" class="form-control @error('verify_code_secondary') is-invalid @enderror" name="verify_code_secondary" value="{{ old('verify_code_secondary', $user->verify_code_secondary) }}" required>

                        @error('verify_code_secondary')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror

                        <div class="mt-2">
                            <p class="mb-0">
                                <button form="send-verify-code-secondary" class="btn btn-link p-0">
                                    {{ __('Send Verification code') }}
                                </button>
                            </p>

                            @if (session('status') === 'resend-verify-code-name')
                                <div class="alert alert-success mt-2">Verification Code sent to your current email.</div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <div class="row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Reset') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
