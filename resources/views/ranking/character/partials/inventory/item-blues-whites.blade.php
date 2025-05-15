<img src="{{ asset('/images/com_itemsign.PNG') }}" class="img-clear" style="display: inline-block" alt="">

@if(in_array((int) $item['TypeID2'], [4], true))
    <span style="color:#50cecd;font-weight: bold;margin-left: 20px">
        {{ $item['ItemName'] }} {{ ($item['OptLevel'] > 0) ? '(+' . $item['OptLevel'] . ')' : ''}}
    </span>
@elseif($item['SoxType'] != 'Normal' || count($item['BlueInfo']) >= 1)
    <span style="color:#{{ $item['SoxType'] != 'Normal' ? 'f2e43d' : '50cecd' }};font-weight: bold;margin-left: 20px">
        {{ $item['ItemName'] }} {{ (($item['OptLevel'] + $item['nOptValue']) > 0) ? '(+' . ($item['OptLevel'] + $item['nOptValue']) . ')' : ''}}
    </span>
@else
    <span style="font-weight: bold;margin-left: 20px">
        {{ $item['ItemName'] }}
    </span>
@endif
<br />
<br />

@if($item['SoxType'] != 'Normal' && !in_array((int) $item['TypeID2'], [4], true))
    <b style="color:#f2e43d;">{{ $item['SoxType'] }}</b><br />
@endif

@if($item['SoxName'] && !in_array((int) $item['TypeID2'], [4], true))
    <span style="color:#53EE92;font-weight: bold;">{{ $item['SoxName'] }}</span><br />
@endif

<span style="color:#efdaa4;">
    @isset($item['Type'])
        {{ __('Sort of item:') }} {{ $item['Type'] }}<br />
    @endisset

    @if(!in_array((int) $item['TypeID3'], [13, 14], true))
        @isset($item['Detail'])
            {{ __('Mounting part:') }} {{ $item['Detail'] }}<br />
        @endisset
        @if(in_array((int) $item['TypeID2'], [4], true))
            @isset($item['Degree'])
                {{ __('Level:') }} {{ $item['JobDegree'] }}<br />
            @endisset
        @else
            @isset($item['Degree'])
                {{ __('Degree: :degree degrees', ['degree' => $item['Degree']]) }}<br />
            @endisset
        @endif

        <br />
    @endif
</span>

@if($item['WhiteInfo'])
    @foreach($item['WhiteInfo'] as $iKey => $sWhites)
        @if(!empty($sWhites))
            {{ $sWhites }}<br />
        @endif
    @endforeach
    <br />
@endif

@if($item['ReqLevel1'])
    @if(in_array((int) $item['TypeID2'], [4], true))
        {{ __('Job level:') }} {{ $item['ReqLevel1'] }}<br />
    @else
        {{ __('Reqiure level:') }} {{ $item['ReqLevel1'] }}<br />
     @endif
@endif

@if(in_array((int) $item['TypeID2'], [1], true) && in_array((int) $item['TypeID3'], [10], true))
    @isset($item['Gender'])
        {{ $item['Gender'] }}<br />
    @endisset
@endif

@if(!in_array((int) $item['TypeID2'], [4], true) && !in_array((int) $item['TypeID3'], [13, 14], true))
    @isset($item['Country'])
        {{ $item['Country'] }}<br />
    @endisset
@endif

@if(in_array((int) $item['TypeID3'], [13, 14], true))
    @if($item['TypeID3'] == 14)
        {{ __('Basic stats (HP/MP) increase when equipped.  Also, upon awakening the bracelet and activating it, the outer appearance becomes extravagant and divine power becomes available to the wearer for a time.') }}
        <br />
        <br />
        {{ __('When awakened, the Awakening Time is counted down.') }}
    @elseif($item['TypeID3'] == 13 && $item['MaxMagicOptCount'] == 0)
        {{ __('Flag with enormous, magnificent dragon pattern engraved. Can be equipped in the job slot.') }}<br />
    @else
        {{ $item['ItemDesc'] ?? 'Dress worn by ' }} <br />
    @endif
    <br />
@endif

@if(!in_array((int) $item['TypeID2'], [4], true) && in_array((int) $item['TypeID3'], [13], true) && in_array((int) $item['TypeID4'], [2], true))
    <span style="color:#efdaa4;">{{ __('Attachment:') }} {{ $item['ChildItemCount'] == 1 ? 'Able to equip' : 'Unable to equip' }}</span>
    <br />
@endif

@if(in_array((int) $item['TypeID2'], [4], true) || in_array((int) $item['TypeID3'], [13], true))
    <span style="color:#efdaa4;">{{ __('Max. no. of magic options: :unit Unit', ['unit' => $item['MaxMagicOptCount']]) }}</span>
    <br />
@endif

@if(in_array((int) $item['TypeID3'], [13], true) && $item['MaxMagicOptCount'] != 0)
    @isset($item['Gender'])
        <br />{{ $item['Gender'] }}<br />
    @endisset
@endif

@if(in_array((int) $item['TypeID3'], [14], true))
    <br />
    <span style="color:#efdaa4;">{{ __('Basic Option') }}</span><br />
    {{ __('MaximumHP :max% Increase', ['max' => $item['DevilMaxHP']]) }}<br />
    {{ __('MaximumMP :max% Increase', ['max' => $item['DevilMaxHP']]) }}<br />
    <br />
@endif

@if(in_array((int) $item['TypeID3'], [14], true) && $item['DevilMaxHP'] > 5)
    <span style="color:#efdaa4;">{{ __('Additional magic Option') }}</span><br />
    <span style="color:#53EE92;">{{ __('Blocking rate :rate Increase', ['rate' => floor(($item['DevilMaxHP'] / 100) * 10)]) }}</span><br />
    <br />
@endif

@if(!in_array((int) $item['TypeID2'], [4], true) && !in_array((int) $item['TypeID3'], [13, 14], true))
    @if($item['MagParam1'] >= 4611686018427387904)
        <span style="color:#ff2f51;">{{ __('You may not use normal Magic Stone') }}</span>
        <br />
        @php $STR = 0 @endphp
        @php $INT = 0 @endphp
        @if($item['BlueInfo'])
            @foreach($item['BlueInfo'] as $value)
                @if($value['code'] == 'MATTR_STR')
                    @php $STR += $value['value'] @endphp
                @endif
                @if($value['code'] == 'MATTR_INT')
                    @php $INT += $value['value'] @endphp
                @endif
            @endforeach

            <span style="color:#efdaa4;">{{ __('Wheels Count:') }} [{{ count($item['BlueInfo']) }}]</span><br />
            <span style="color:#efdaa4;">{{ __('STR Count:') }} [{{ $STR }}]</span><br />
            <span style="color:#efdaa4;">{{ __('INT Count:') }} [{{ $INT }}]</span><br />
        @endif
    @endif
@endif

@if($item['BlueInfo'])
    <br />
    @foreach($item['BlueInfo'] as $value)
        <b style="color:#{{ $value['code'] == 'MATTR_DEC_MAXDUR' ? 'ff2f51' : '50cecd' }}">{{ $value['name'] }} {{--@if(isset($value['mLevel']) && $value['mLevel'] > 0) (+{{ round($value['value'] / $value['mLevel']) * 100 }}%) @endif--}}</b><br />
    @endforeach
@endif

@if(!in_array((int) $item['TypeID2'], [4], true) && !in_array((int) $item['TypeID3'], [13, 14], true))
    @if(!isset($item['nOptValue']))
        {{ __('Able to use Advanced elixir.') }}
    @else
        <b>{{ __('Advanced elixir is in effect') }} [+{{ $item['nOptValue'] }}]</b>
    @endif
@endif

@if(in_array((int) $item['TypeID3'], [14], true))
    <br/><span style="color:#efdaa4;font-weight:bold;">{{ __('Awaken period') }}</span><br/>
    @isset($item['TimeEnd'])
        {{ $item['TimeEnd'] }}<br/>
    @endisset
@endif
