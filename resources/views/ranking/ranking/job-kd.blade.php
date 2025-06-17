<div class="table-responsive">
    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th scope="col">{{ __('Rank') }}</th>
                <th scope="col">{{ __('Name') }}</th>
                <th scope="col">{{ __('Kill/Death') }}</th>
            </tr>
        </thead>
        <tbody>
        @php $i = 1; @endphp
        @forelse($data as $value)
            <tr>
                <td>
                    @if($i <= 3)
                        <img src="{{ asset($topImage[$i]) }}" alt=""/>
                    @else
                        {{ $i }}
                    @endif
                </td>
                <td>
                    <a href="{{ route('ranking.character.view', ['name' => $value->CharName]) }}" class="text-decoration-none">{{ $value->CharName }}</a>
                </td>
                <td>{{ $value->KillCount }} / {{ $value->DeathCount }}</td>
            </tr>
            @php $i++ @endphp
        @empty
            <tr>
                <td colspan="3" class="text-center">{{ __('No Records Found!') }}</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
