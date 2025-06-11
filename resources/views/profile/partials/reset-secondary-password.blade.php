<div class="card">
    <div class="card-header">{{ __('Reset Secondry Password') }}</div>

    <div class="card-body">
        @if(session('passcode_success'))
            <div class="alert alert-success">{{ session('passcode_success') }}</div>
        @endif

        @if(session('passcode_error'))
            <div class="alert alert-danger">{{ session('passcode_error') }}</div>
        @endif

        <form method="POST" action="{{ route('profile.reset.secondary.password') }}">
            @csrf

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
