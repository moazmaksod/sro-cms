@isset ($soxPlusConfig['enabled'])
    <div class="card mb-4">
        <div class="card-header">
            {{ __('Sox Plus') }}
        </div>
        <div class="card-body">
            <ul class="list-unstyled">
            @forelse($soxPlus as $value)
                <li>
                    <p>
                        <img src="{{ asset('images/sro/' . $value->AssocFileIcon128 . '.png') }}" alt="" width="32" height="32" class="">
                        @if(!empty($value->CharName16))
                            [<a href="{{ route('ranking.character.view', ['name' => $value->CharName16]) }}" class="text-decoration-none">{{ $value->CharName16 }}</a>]
                        @else
                            [{{ __('NoName') }}]
                        @endif
                        {{ __('has successfully enchanted their') }}
                        @if(config('global.server.version') === 'vSRO')
                            [{{ $value->RealName }}]
                        @else
                            [{{ $value->ENG }}]
                        @endif
                        {{ __('to') }}
                        [+{{ $value->PlusValue }}]
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
