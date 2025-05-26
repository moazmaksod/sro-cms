<div class="table-responsive">
    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th scope="col">{{ __('Rank') }}</th>
                @foreach(array_keys((array) $data->first()) as $col)
                    <th>{{ ucfirst($col) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @php $i = 1; @endphp
            @if($data->isNotEmpty())
                @foreach($data as $index => $entry)
                    <tr>
                        <td>
                            @if($i <= 3)
                                <img src="{{ asset($topImage[$i]) }}" alt=""/>
                            @else
                                {{ $i }}
                            @endif
                        </td>
                        @foreach((array) $entry as $value)
                            <td>{{ $value }}</td>
                        @endforeach
                    </tr>
                    @php $i++ @endphp
                @endforeach
            @else
                <p class="text-center">{{ __('No Records Found!') }}</p>
            @endif
        </tbody>
    </table>
</div>
