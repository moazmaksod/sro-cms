<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>{{ __('Rank') }}</th>
                <th>{{ __('Name') }}</th>
                <th>{{ __('Kill/Death') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $key => $row)
            <tr>
                <td>
                    @if($key < 3)
                        <img src="{{ asset(config('ranking.top_image')[$key + 1]) }}" alt=""/>
                    @else
                        {{ $key + 1 }}
                    @endif
                </td>
                <td>
                    <a href="{{ route('ranking.character.view', ['name' => $row->CharName]) }}" class="text-decoration-none">{{ $row->CharName }}</a>
                </td>
                <td>{{ $row->KillCount }} / {{ $row->DeathCount }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="3" class="text-center">{{ __('No Records Found!') }}</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
