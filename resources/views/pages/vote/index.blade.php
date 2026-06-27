@extends('layouts.app')
@section('title', __('Vote Sites'))

@section('sidebar')
    @include('account.sidebar')
@stop

@section('content')
    <section class="card">
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="row">
                @foreach($data as $key => $row)
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <img class="d-block m-auto object-fit-cover rounded border mb-2" src="{{ $row->image }}" alt="" style="min-width: 90px; min-height: 50px;"/>
                                <p class="mb-0">{{ $row->name }}</p>
                                <p class="text-muted mb-0">{{ __('Reward:') }} {{ $row->reward }} Silk</p>
                                <p class="text-muted mb-2">{{ __('Timeout:') }} {{ $row->timeout }} Hours</p>

                                @if(!$row->enabled)
                                    {{ __('Disabled') }}
                                @elseif($row->expire)
                                    {{ __('Wait until') }} {{ $row->expire?->format('Y-m-d H:i') }}
                                @else
                                    <a href="#" target="_blank" class="btn btn-primary vote-btn" data-site="{{ $key }}" data-url="{{ route('account.vote.voting', $key) }}">Vote Now</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@fingerprintjs/fingerprintjs@3/dist/fp.min.js"></script>
    <script>
        (async () => {
            const fp = await FingerprintJS.load();
            const result = await fp.get();
            const fingerprint = result.visitorId;

            document.querySelectorAll('.vote-btn').forEach(btn => {
                btn.addEventListener('click', e => {
                    e.preventDefault();
                    const url = btn.dataset.url + '?fingerprint=' + fingerprint;
                    window.open(url, '_blank');
                });
            });
        })();
    </script>
@endpush
