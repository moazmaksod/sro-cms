<div class="card">
    <div class="card-header">{{ __('Update Password') }}</div>

    <div class="card-body">
        <div class="mb-3">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </div>

        @if(config('global.account_verify', 0))
            <form id="send-verify-code" method="POST" action="{{ route('account.resend.verify.code') }}">
                @csrf
                <input type="hidden" name="context" id="verify-context">
            </form>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            @method('put')

            @if(!config('global.account_verify', 0))
                @include('account.partials.input._password', ['name' => 'current_password'])
            @else
                @include('account.partials.input._verify_code', ['name' => 'verify_code_password'])
            @endif

            <div class="row mb-3">
                <label for="password" class="col-lg-4 col-form-label text-md-end">
                    {{ __('New Password') }}
                </label>

                <div class="col-lg-6">
                    <input id="password" type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" name="password" required autocomplete="new-password">

                    @error('password', 'updatePassword')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="password_confirmation" class="col-lg-4 col-form-label text-md-end">
                    {{ __('Confirm Password') }}
                </label>

                <div class="col-lg-6">
                    <input id="password_confirmation" type="password" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" name="password_confirmation" required>

                    @error('password_confirmation', 'updatePassword')
                    <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-0">
                <div class="col-lg-6 offset-md-4">
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
