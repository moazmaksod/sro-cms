<div class="sro-item-detail {{ $item['ItemInfo']['SoxType'] != 'Normal' ? 'sro-item-special' : '' }}">
    <div class="item" data-itemInfo="1">
        @if($item['ItemInfo']['SoxType'] != 'Normal' && !in_array((int) $item['ItemInfo']['TypeID2'], [4], true))
        <img alt="" class="sro-item-special-seal" src="{{ asset('/images/seal.gif') }}" />
        @endif
        {{--
        @if(config("settings.item_stats_jid_{$userJID}"))
        <img alt="" class="sro-item-special-seal" src="{{ asset('/images/sro/icon_disable.png') }}" />
        @endif
        --}}
        @if(file_exists(public_path($item['ImgPath'])))
        <img alt="" src="{{ asset(strtolower($item['ImgPath'])) }}" @if(config("settings.item_stats_jid_{$userJID}")) style="filter: blur(2px);" @endif>
        @else
        <img alt="" src="{{ asset('/images/sro/icon_default.png') }}">
        @endif
        @if($item['Amount'])
        <span class="amount" style="position: absolute; font-size: 9px">{{ $item['Amount'] }}</span>
        @endif
    </div>
    <?php if ($item) : ?>
    <div class="info">
        @if(!config("settings.item_stats_jid_{$userJID}"))
            @include('ranking.character.partials.inventory.item-blues-whites', ['item' => $item['ItemInfo']])
        @else
            <span style="color:#ff2f51">{{ __('The Information Hidden by User') }}</span><br />
        @endif
    </div>
    <?php endif; ?>
    <div class="clearfix"></div>
</div>
