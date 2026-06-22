<div class="card">
    <div class="card-header">{{ __('Reset Secondry Password') }}</div>

    <div class="card-body">
        @if(session('passcode_success'))
            <div class="alert alert-success">{{ session('passcode_success') }}</div>
        @endif

        @if(session('passcode_error'))
            <div class="alert alert-danger">{{ session('passcode_error') }}</div>
        @endif

        @if(config('global.account_verify', 0))
            <form id="send-verify-code" method="POST" action="{{ route('account.resend.verify.code') }}">
                @csrf
                <input type="hidden" name="context" id="verify-context">
            </form>
        @endif

        <form method="POST" action="{{ route('account.reset.secondary.password') }}">
            @csrf

            @if(!config('global.account_verify', 0))
                @include('account.partials.input._password')
            @else
                @include('account.partials.input._verify_code', ['name' => 'verify_code_secondary'])
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
