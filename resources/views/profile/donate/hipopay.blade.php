@section('title', __('HipoPay'))

@forelse($data['package'] as $value)
    <div class="card mb-2" data-name="{{ $value['name'] }}" data-price="{{ $value['price'] }}" data-currency="{{ $data['currency'] }}">
        <div class="card-body d-flex justify-content-between align-items-center">
            <strong>{{ $value['name'] }}</strong>
            <span>{{ $data['currency'] }} {{ $value['price'] }}</span>
        </div>
    </div>
@empty
    <p class="text-muted">{{ __('No Packages Available!') }}</p>
@endforelse
