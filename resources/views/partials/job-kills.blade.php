@isset ($jobKillsConfig['enabled'])
    <div class="card mb-4">
        <div class="card-header">
            {{ __('Job Kills') }}
        </div>
        <div class="card-body">
            <ul class="list-unstyled">
                @forelse($jobKills as $value)
                    <li>
                        <p>
                            @if($value->KillerCharName)
                                <a href="{{ route('ranking.character.view', ['name' => $value->KillerCharName]) }}" class="text-decoration-none">{{ $value->KillerCharName }}</a>
                            @endif
                            {{ __('Has killed:') }}
                            @if($value->DeadCharName)
                                <a href="{{ route('ranking.character.view', ['name' => $value->DeadCharName]) }}" class="text-decoration-none">{{ $value->DeadCharName }}</a>
                            @endif
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
