<div class="table-responsive">
    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th scope="col">{{ __('Unique Name') }}</th>
                <th scope="col">{{ __('Points') }}</th>
                <th scope="col">{{ __('Time') }}</th>
            </tr>
        </thead>
        <tbody>
            @forelse($uniqueHistory as $value)
                <tr>
                    <td>{{ $uniqueList[$value->Value]['name'] }}</td>
                    <td>+{{ $uniqueList[$value->Value]['points'] }}</td>
                    <td>{{ \Carbon\Carbon::make($value->EventTime)->diffForHumans() }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">{{ __('No Records Found!') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
