@section('title', __('HipoPay'))

@forelse($data['package'] as $value)
    <div class="card mb-2" onclick="this.nextElementSibling.submit();">
        <div class="card-body d-flex justify-content-between align-items-center">
            <strong>{{ $value['name'] }}</strong>
            <span>{{ $data['currency'] }} {{ $value['price'] }}</span>
        </div>
    </div>

    <form action="{{ route('profile.donate.process', ['method' => $data['route']]) }}" method="POST">
        @csrf
        <input type="hidden" name="price" value="{{ $value['price'] }}">
    </form>
@empty
    <p class="text-muted">{{ __('No Packages Available!') }}</p>
@endforelse
