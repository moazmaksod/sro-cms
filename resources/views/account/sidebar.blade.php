@auth
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">{{ __('Welcome') }}, {{ auth()->user()->username }}</h5>

            <ul class="list-group mb-3">
                @if(config('global.server.version') === 'vSRO')
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>{{ __('Silk') }}</strong>
                        <span class="">{{ number_format(auth()->user()->tbUser->getSilk->silk_own) }}</span>
                    </li>
                @else
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>{{ __('Premium Silk') }}</strong>
                        <span class="">{{ number_format(auth()->user()->muUser->getSilk->PremiumSilk) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <strong>{{ __('VIP') }}</strong>
                        <span class="">
                            @isset(auth()->user()->muUser->muVIPInfo->VIPUserType)
                                <img src="{{ asset(config('ranking.vip_level')['level'][auth()->user()->muUser->muVIPInfo->VIPLv]['image']) }}" width="24" height="24" alt="">
                                <span>{{ config('ranking.vip_level')['level'][auth()->user()->muUser->muVIPInfo->VIPLv]['name'] }}</span>
                            @else
                                <span>{{ __('None') }}</span>
                            @endisset
                        </span>
                    </li>
                @endif
            </ul>

            <p class="mb-0">{{ __('Account Panel') }}</p>
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a href="{{ route('account') }}" class="nav-link {{ request()->routeIs('account') ? 'active' : '' }}">
                        {{ __('Account Info') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('account.edit') }}" class="nav-link {{ request()->routeIs('account.edit') ? 'active' : '' }}">
                        {{ __('Settings') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('account.donate') }}" class="nav-link {{ request()->routeIs('account.donate') ? 'active' : '' }}">
                        {{ __('Donate') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('account.donate.history') }}" class="nav-link {{ request()->routeIs('account.donate.history') ? 'active' : '' }}">
                        {{ __('Donate History') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('account.voucher') }}" class="nav-link {{ request()->routeIs('account.voucher') ? 'active' : '' }}">
                        {{ __('Voucher') }}
                    </a>
                </li>
                @if(config('global.vote.enabled', false))
                <li class="nav-item">
                    <a href="{{ route('account.vote') }}" class="nav-link {{ request()->routeIs('account.vote') ? 'active' : '' }}">
                        {{ __('Vote4Silk') }}
                    </a>
                </li>
                @endif
                @if(config('global.referral.enabled', false))
                <li class="nav-item">
                    <a href="{{ route('account.referral') }}" class="nav-link {{ request()->routeIs('account.referral') ? 'active' : '' }}">
                        {{ __('Referral') }}
                    </a>
                </li>
                @endif
                @if(config('global.tickets.enabled', false))
                <li class="nav-item">
                    <a href="{{ route('account.tickets') }}" class="nav-link {{ request()->routeIs('account.tickets') ? 'active' : '' }}">
                        {{ __('Tickets') }}
                    </a>
                </li>
                @endif
                <li class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); fetch('{{ route('logout') }}', {method:'POST', headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'}}).then(() => window.location.href = '/')">
                        {{ __('Logout') }}
                    </a>
                </li>
            </ul>
        </div>
    </div>
@endauth
