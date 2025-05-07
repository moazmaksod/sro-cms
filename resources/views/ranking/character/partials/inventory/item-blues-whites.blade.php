<img src="{{ asset('/images/com_itemsign.PNG') }}" class="img-clear" style="display: inline-block" alt="">

@if($item['SoxType'] != 'Normal' || count($item['BlueInfo']) >= 1)
    <span style="color:#{{ $item['SoxType'] != 'Normal' ? 'f2e43d' : '50cecd' }};font-weight: bold;margin-left: 20px">
        {{ $item['ItemName'] }} [+{{ $item['OptLevel'] + $item['nOptValue'] }}]
    </span>
@else
    <span style="font-weight: bold;margin-left: 20px">
        {{ $item['ItemName'] }}
    </span>
@endif
<br />
<br />

@if($item['SoxType'] != 'Normal')
    <b style="color:#f2e43d;">{{ $item['SoxType'] }}</b><br />
@endif

@if($item['SoxName'])
    <span style="color:#53EE92;font-weight: bold;">{{ $item['SoxName'] }}</span><br />
@endif

<span style="color:#efdaa4;">
    @isset($item['Type'])
        {{ __('Sort of item:') }} {{ $item['Type'] }}<br />
    @endisset
    @isset($item['Detail'])
        {{ __('Mounting part:') }} {{ $item['Detail'] }}<br />
    @endisset
    @if(!count(array_intersect([13, 14], explode(',', $item['TypeID3']))))
        @if(count(array_intersect([4], explode(',', $item['TypeID2']))))
            @isset($item['Degree'])
                {{ __('Level:') }} {{ $item['JobDegree'] }}<br />
            @endisset
        @else
            @isset($item['Degree'])
                {{ __('Degree: :degree degrees', ['degree' => $item['Degree']]) }}<br />
            @endisset
        @endif
    @endif
</span>
<br />

@if($item['WhiteInfo'])
    @foreach($item['WhiteInfo'] as $iKey => $sWhites)
        @if(!empty($sWhites))
            {{ $sWhites }}<br />
        @endif
    @endforeach
    <br />
@endif

@if($item['ReqLevel1'])
    @if(count(array_intersect([4], explode(',', $item['TypeID2']))))
        {{ __('Job level:') }} {{ $item['ReqLevel1'] }}<br />
    @else
        {{ __('Reqiure level:') }} {{ $item['ReqLevel1'] }}<br />
     @endif
@endif

{{--
@if(!count(array_intersect([13, 14], explode(',', $item['TypeID3']))))
    @isset($item['Gender'])
        {{ $item['Gender'] }}<br />
    @endisset
@endif
--}}

@if(!count(array_intersect([13, 14], explode(',', $item['TypeID3']))))
    @isset($item['Country'])
        {{ $item['Country'] }}<br />
    @endisset
@endif

@if(count(array_intersect([13, 14], explode(',', $item['TypeID3']))))
    @if($item['TypeID3'] == 14)
        {{ __('Basic stats (HP/MP) increase when equipped.  Also, upon awakening the bracelet and activating it, the outer appearance becomes extravagant and divine power becomes available to the wearer for a time.') }}
        <br />
        <br />
        {{ __('When awakened, the Awakening Time is counted down.') }}
    @elseif($item['TypeID3'] == 13 && $item['MaxMagicOptCount'] == 0)
        {{ __('Flag with enormous, magnificent dragon pattern engraved. Can be equipped in the job slot.') }}<br />
    @else
        {{ __('Dress worn by') }} {{ $item['ItemName'] }}<br />
    @endif
    <br />
@endif

@if(count(array_intersect([13], explode(',', $item['TypeID3']))))
    <span style="color:#efdaa4;">{{ __('Max. no. of magic options: :unit Unit', ['unit' => $item['MaxMagicOptCount']]) }}</span>
    <br />
@elseif(count(array_intersect([4], explode(',', $item['TypeID2']))))
    <span style="color:#efdaa4;">{{ __('Max. no. of magic options: :unit Unit', ['unit' => $item['MaxMagicOptCount']]) }}</span>
    <br />
@endif

@if(count(array_intersect([13], explode(',', $item['TypeID3']))))
    @isset($item['Gender'])
        <br />{{ $item['Gender'] }}<br />
    @endisset
@endif

@if(count(array_intersect([14], explode(',', $item['TypeID3']))))
    <br />
    <span style="color:#efdaa4;">{{ __('Basic Option') }}</span><br />
    {{ __('MaximumHP 15% Increase') }}<br />
    {{ __('MaximumMP 15% Increase') }}<br />
    <br />
@endif

@if(!count(array_intersect([13, 14], explode(',', $item['TypeID3']))))
    @if(!count(array_intersect([4], explode(',', $item['TypeID2']))))
        @if($item['MagParam1'] >= 4611686018427387904)
            <span style="color:#ff2f51;">{{ __('You may not use normal Magic Stone') }}</span>
            <br />
            @php $STR = 0 @endphp
            @php $INT = 0 @endphp
            @if($item['BlueInfo'])
                @foreach($item['BlueInfo'] as $value)
                    @if($value['code'] == 'MATTR_STR')
                        @php $STR += $value['mValue'] @endphp
                    @endif
                    @if($value['code'] == 'MATTR_INT')
                        @php $INT += $value['mValue'] @endphp
                    @endif
                @endforeach

                <span style="color:#efdaa4;">{{ __('Wheels Count:') }} [{{ count($item['BlueInfo']) }}]</span><br />
                <span style="color:#efdaa4;">{{ __('STR Count:') }} [{{ $STR }}]</span><br />
                <span style="color:#efdaa4;">{{ __('INT Count:') }} [{{ $INT }}]</span><br />
            @endif
        @endif
    @endif
@endif

@if($item['BlueInfo'])
    <br />
    @foreach($item['BlueInfo'] as $value)
        @if($value['id'] === 65)
            <b style="color:#ff2f51">Repair invalid (Maximum durability 400% increase)</b><br />
        @else
            {{--<b style="color:#{{ $value['code'] == 'MATTR_DEC_MAXDUR' ? 'ff2f51' : '50cecd' }}">{{ $value['name'] }} @if(isset($value['mLevel']) && $value['mLevel'] > 0) (+{{ round($value['value'] / $value['mLevel']) * 100 / 100 }}%) @endif</b><br />--}}
            <b style="color:#{{ $value['code'] == 'MATTR_DEC_MAXDUR' ? 'ff2f51' : '50cecd' }}">{{ $value['name'] }}</b><br />
        @endif
    @endforeach
@endif

@if(!count(array_intersect([13, 14], explode(',', $item['TypeID3']))))
    @if(!count(array_intersect([4], explode(',', $item['TypeID2']))))
        @if(!isset($item['nOptValue']))
            {{ __('Able to use Advanced elixir.') }}
        @else
            <b>{{ __('Advanced elixir is in effect') }} [+{{ $item['nOptValue'] }}]</b>
        @endif
    @endif
@endif

@if(count(array_intersect([14], explode(',', $item['TypeID3']))))
    <br/><span style="color:#efdaa4;font-weight:bold;">{{ __('Awaken period') }}</span><br/>
    @isset($item['TimeEnd'])
        {{ $item['TimeEnd'] }}<br/>
    @endisset
@endif
