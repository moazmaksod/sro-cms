<div class="table-responsive">
    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th scope="col">{{ __('Rank') }}</th>
                <th scope="col">{{ __('NickName') }}</th>
                <th scope="col">{{ __('JobLevel') }}</th>
                <th scope="col">{{ __('Kills') }}</th>
                <th scope="col">{{ __('Points') }}</th>
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
                        @if($value->RefObjID > 2000)
                            <img src="{{ asset($characterRace[1]['image']) }}" width="16" height="16" alt=""/>
                        @else
                            <img src="{{ asset($characterRace[0]['image']) }}" width="16" height="16" alt=""/>
                        @endif
                        <a href="{{ route('ranking.character.view', ['name' => $value->CharName16]) }}" class="text-decoration-none">{{ $value->NickName16 }}</a>
                    </td>
                    <td>{{ $value->JobLevel }}</td>
                    <td>{{ $value->KillCount }}</td>
                    <td>{{ $value->ReputationPoint }}</td>
                </tr>
                @php $i++ @endphp
            @empty
                <tr>
                    <td colspan="5" class="text-center">{{ __('No Records Found!') }}</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
