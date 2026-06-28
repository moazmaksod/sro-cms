@section('title', __('HipoCard'))

<div class="card mb-4">
    <div class="card-body">
        <p class="text-center mb-0">Enter your e-pin card</p>
        <form method="post" action="{{ route('account.donate.process', ['method' => $data['route']]) }}">
            @csrf

            <div class="mb-3">
                <label for="code" class="form-label">{{ __('E-Pin Code') }}</label>
                <input id="code" type="text" class="form-control @error('code') is-invalid @enderror" name="code" required>

                @error('code')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">{{ __('E-Pin Password') }}</label>
                <input id="password" type="text" class="form-control @error('password') is-invalid @enderror" name="password" required>

                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary w-100">{{ __('Submit') }}</button>
        </form>
    </div>
</div>

@forelse($data['package'] as $row)
    <div class="card mb-2" data-name="{{ $row['name'] }}" data-price="{{ $row['price'] }}" data-currency="{{ $data['currency'] }}" data-type="{{ $row['type'] ?? (config('global.server.version') === 'vSRO' ? 0 : 3) }}">
        <div class="card-body d-flex justify-content-between align-items-center">
            <strong>{{ $row['name'] }}</strong>
            <span>{{ $data['currency'] }} {{ $row['price'] }}</span>
        </div>
    </div>
@empty
    <p class="text-muted">{{ __('No Packages Available!') }}</p>
@endforelse
