@if(config('ranking.extra.character_global_history', false))
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>{{ __('Message') }}</th>
                <th>{{ __('Time') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data->globalHistory as $row)
                <tr>
                    <td>{{ $row->Comment }}</td>
                    <td>{{ \Carbon\Carbon::make($row->EventTime)->diffForHumans() }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="text-center">{{ __('No Records Found!') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endif
