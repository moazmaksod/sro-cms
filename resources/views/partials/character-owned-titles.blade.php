@if(isset($config) && $config['enabled'])
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('Title Name') }}</th>
                    <th>{{ __('Enable') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $row)
                    <tr>
                        <th>{{ $row->TitleName ?? $row->TitleString }}</th>
                        <td>{{ $row->Enable }}</td>
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
