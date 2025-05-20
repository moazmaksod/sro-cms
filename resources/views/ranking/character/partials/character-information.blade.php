<div class="table-responsive">
    <table class="table table-striped">
        <tbody>
        <tr>
            <td>{{ __('Character Name:') }}</td>
            <td>{{ $data->CharName16 }}</td>
        </tr>
        <tr>
            <td>{{ __('JobName:') }}</td>
            @if(!config("settings.job_name_jid_{$userJID}"))
                @if(!empty($data->NickName16))
                    <td>{{ $data->NickName16 }}</td>
                @else
                    <td>{{ __('None') }}</td>
                @endif
            @else
                <td>{{ __('Hidden') }}</td>
            @endif
        </tr>
        <tr>
            <td>{{ __('Guild:') }}</td>
            <td>
                @if($data->ID > 0)
                    <a href="{{ route('ranking.guild.view', ['name' => $data->Name]) }}" class="text-decoration-none">{{ $data->Name }}</a>
                @else
                    <span>{{ __('None') }}</span>
                @endif
            </td>
        </tr>
        <tr>
            <td>{{ __('Race:') }}</td>
            <td>
                @if($data->RefObjID > 2000)
                    <img src="{{ asset($characterRace[1]['image']) }}" width="16" height="16" alt=""/>
                    <span>{{ $characterRace[1]['name'] }}</span>
                @else
                    <img src="{{ asset($characterRace[0]['image']) }}" width="16" height="16" alt=""/>
                    <span>{{ $characterRace[0]['name'] }}</span>
                @endif
            </td>
        </tr>
        <tr>
            <td>{{ __('Level:') }}</td>
            <td>{{ $data->CurLevel }} / {{ config('settings.max_level') }}</td>
        </tr>
        <tr>
            <td>{{ __('Item Points:') }}</td>
            <td>{{ $data->ItemPoints }}</td>
        </tr>
        <tr>
            <td>{{ __('Title:') }}</td>
            <td style="color: #ffc345">
                @if($data->HwanLevel > 0)
                    @if($data->RefObjID > 2000)
                        [{{ $hwanLevel[1][$data->HwanLevel] ?? '' }}]
                    @else
                        [{{ $hwanLevel[2][$data->HwanLevel] ?? '' }}]
                    @endif
                @else
                    []
                @endif
            </td>
        </tr>
        </tbody>
    </table>
</div>
