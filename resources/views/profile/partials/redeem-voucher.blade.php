<div class="card">
    <div class="card-header">{{ __('Redeem Voucher') }}</div>

    <div class="card-body">
        @if(session('voucher_success'))
            <div class="alert alert-success">{{ session('voucher_success') }}</div>
        @endif

        @if(session('voucher_error'))
            <div class="alert alert-danger">{{ session('voucher_error') }}</div>
        @endif

        <form method="POST" action="{{ route('profile.redeem') }}">
            @csrf

            <div class="row mb-3">
                <label for="voucher_code" class="col-md-4 col-form-label text-md-end">
                    {{ __('Voucher Code:') }}
                </label>

                <div class="col-md-6">
                    <input id="voucher_code" type="text" class="form-control @error('voucher_code') is-invalid @enderror" name="voucher_code" placeholder="Enter Voucher Code" required>

                    @error('voucher_code')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-0">
                <div class="col-md-6 offset-md-4">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Redeem') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
