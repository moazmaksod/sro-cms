@if ($config['enabled'])
    <div class="card mb-4">
        <div class="card-header">
            {{ __('Fortress War') }}
        </div>
        <div class="card-body">
            <ul class="list-unstyled">
                @forelse($data as $value)
                    <li>
                        <span>
                            <img src="{{ asset($config['names'][$value->FortressID]['image']) }}" alt="">
                            {{ $config['names'][$value->FortressID]['name'] }}
                        </span>
                        <span class="float-end">
                            @if($value->Name !== 'DummyGuild')
                                <a href="{{ route('ranking.guild.view', ['name' => $value->Name]) }}" class="text-decoration-none">{{ $value->Name }}</a>
                            @else
                                <span>{{ __('None') }}</span>
                            @endif
                        </span>
                    </li>
                @empty
                    <p class="text-center">{{ __('No Records Found!') }}</p>
                @endforelse
            </ul>
        </div>
    </div>
@endif
