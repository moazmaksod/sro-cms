@if(config('ranking.extra.character_unique_history', false))
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>{{ __('Unique Name') }}</th>
                <th>{{ __('Points') }}</th>
                <th>{{ __('Time') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data->uniqueHistory as $row)
                <tr>
                    <td>{{ config('ranking.uniques')[$row->Value]['name'] }}</td>
                    <td>+{{ config('ranking.uniques')[$row->Value]['points'] }}</td>
                    <td>{{ \Carbon\Carbon::make($row->EventTime)->diffForHumans() }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">{{ __('No Records Found!') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endif
