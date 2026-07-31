<div class="table-responsive">
    <table class="table">
        <thead>
        <tr>
            <th>{{ __('Rank') }}</th>
            <th>{{ __('Name') }}</th>
            <th>{{ __('Points') }}</th>
        </tr>
        </thead>
        <tbody>
            @forelse($data as $key => $row)
                <tr>
                    <td>
                        <img src="{{ asset(config('ranking.honor_level')[$row->Rank]) }}" alt=""/>
                    </td>
                    <td>
                        @if($row->RefObjID > 2000)
                            <img src="{{ asset(config('ranking.character_race')[1]['image']) }}" width="16" height="16" alt=""/>
                        @else
                            <img src="{{ asset(config('ranking.character_race')[0]['image']) }}" width="16" height="16" alt=""/>
                        @endif
                        <a href="{{ route('ranking.character.view', ['name' => $row->CharName16]) }}" class="text-decoration-none">{{ $row->CharName16 }}</a>
                    </td>
                    <td>{{ $row->HonorPoint }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">{{ __('No Records Found!') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
