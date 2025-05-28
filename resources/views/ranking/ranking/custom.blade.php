@php
    $allColumns = array_keys((array) $data->first());
    $excludedColumns = ['ID', 'CharID', 'GuildID', 'RefObjID'];
    $hasRefObjID = in_array('RefObjID', $allColumns);
    $columns = array_filter($allColumns, fn($col) => !in_array($col, $excludedColumns));
@endphp
<div class="table-responsive">
    <table class="table table-striped">
        <thead class="table-dark">
        <tr>
            <th scope="col">{{ __('Rank') }}</th>
            @foreach($columns as $col)
                <th>{{ ucfirst($col) }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @php $i = 1; @endphp
        @if($data->isNotEmpty())
            @foreach($data as $entry)
                <tr>
                    <td>
                        @if($i <= 3)
                            <img src="{{ asset($topImage[$i]) }}" alt=""/>
                        @else
                            {{ $i }}
                        @endif
                    </td>
                    @foreach($columns as $col)
                        <td>
                            @if($col === 'CharName')
                                @if($hasRefObjID)
                                    @if($entry->RefObjID > 2000)
                                        <img src="{{ asset($characterRace[1]['image']) }}" width="16" height="16" alt=""/>
                                    @else
                                        <img src="{{ asset($characterRace[0]['image']) }}" width="16" height="16" alt=""/>
                                    @endif
                                @endif
                                <a href="{{ route('ranking.character.view', ['name' => $entry->$col]) }}" class="text-decoration-none">{{ $entry->$col }}</a>
                            @elseif($col === 'GuildName')
                                <a href="{{ route('ranking.guild.view', ['name' => $entry->$col]) }}" class="text-decoration-none">{{ $entry->$col }}</a>
                            @else
                                {{ $entry->$col }}
                            @endif
                        </td>
                    @endforeach
                </tr>
                @php $i++ @endphp
            @endforeach
        @else
            <tr>
                <td colspan="{{ count($columns) + 1 }}" class="text-center">{{ __('No Records Found!') }}</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
