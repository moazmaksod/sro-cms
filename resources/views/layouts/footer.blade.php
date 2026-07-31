<footer>
    <div class="container">
        <div class="row py-5">
            <div class="col-lg-6 mb-3">
                <a href="{{ url('/') }}" class="d-flex align-items-center mb-3 text-decoration-none" aria-label="Bootstrap">
                    <img src="{{ asset(config('global.site_logo')) }}" alt="" width="" height="64">
                </a>
                <p class="text-body-secondary">© {{ now()->year }} <a href="{{ config('app.url') }}">{{ config('app.name') }}</a>. {{ __('All Rights Reserved.') }}</p>
            </div>
            <div class="col-lg-2 mb-3">
                <h5>{{ __('General') }}</h5>
                <ul class="nav flex-column">
                    @foreach(config('global.footer')['general'] as $row)
                    <li class="nav-item mb-2"><a href="{{ $row['url'] }}" class="nav-link p-0 text-body-secondary">{{ __($row['name']) }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="col-lg-2 mb-3">
                <h5>{{ __('Social Media') }}</h5>
                <ul class="nav flex-column">
                    @foreach(config('global.footer')['social'] as $row)
                    <li class="nav-item mb-2"><a href="{{ $row['url'] }}" class="nav-link p-0 text-body-secondary">{!! $row['image'] !!} {{ $row['name'] }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="col-lg-2 mb-3">
                <h5>{{ __('Backlink') }}</h5>
                <ul class="nav flex-column">
                    @foreach(config('global.footer')['backlink'] as $row)
                    <li class="nav-item mb-2"><a href="{{ $row['url'] }}" class="nav-link p-0 text-body-secondary"><img src="{{ $row['image'] }}" alt="" width="" height="32"> {{ $row['name'] }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</footer>

