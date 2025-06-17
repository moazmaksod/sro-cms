@isset ($soxDropConfig['enabled'])
    <div class="card mb-4">
        <div class="card-header">
            {{ __('Sox Drop') }}
        </div>
        <div class="card-body">
            <ul class="list-unstyled">
            @forelse($soxDrop as $value)
                <li>
                    <p>
                        <img src="{{ asset('images/sro/' . $value->AssocFileIcon128 . '.png') }}" alt="" width="32" height="32" class="">
                        @if(!empty($value->CharName16))
                            [<a href="{{ route('ranking.character.view', ['name' => $value->CharName16]) }}" class="text-decoration-none">{{ $value->CharName16 }}</a>]
                        @else
                            [{{ __('NoName') }}]
                        @endif
                        {{ __('has just obtained a') }}
                        @if(config('global.server.version') === 'vSRO')
                            [{{ $value->RealName }}]
                        @else
                            [{{ $value->ENG }}]
                        @endif
                        {{ __('The Item was obtained from:') }}
                        [{{ $value->MobCode }}]
                        {{ \Carbon\Carbon::make($value->EventTime)->diffForHumans() }}
                    </p>
                    <hr>
                </li>
            @empty
                <p class="text-center">{{ __('No Records Found!') }}</p>
            @endforelse
            </ul>
        </div>
    </div>
@endisset
