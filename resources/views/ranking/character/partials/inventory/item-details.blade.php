<div class="sro-item-detail {{ $item['ItemInfo']['SoxType'] != 'Normal' ? 'sro-item-special' : '' }}">
    <div class="item" data-itemInfo="1">
        @if($item['ItemInfo']['SoxType'] != 'Normal' && !in_array((int) $item['ItemInfo']['TypeID2'], [4], true))
        <img alt="" class="sro-item-special-seal" src="{{ asset('/images/seal.gif') }}" />
        @endif
        @if(file_exists(public_path($item['ImgPath'])))
        <img alt="" src="{{ asset(strtolower($item['ImgPath'])) }}" @if(config("settings.item_stats_jid_{$data->user->UserJID}") && !auth()->user()?->role?->is_admin) style="filter: blur(2px); z-index: 5" @endif>
        @else
        <img alt="" src="{{ asset('/images/sro/icon_default.png') }}" @if(config("settings.item_stats_jid_{$data->user->UserJID}") && !auth()->user()?->role?->is_admin) style="filter: blur(2px); z-index: 5" @endif>
        @endif
        @if($item['Amount'])
        <span class="amount" style="position: absolute; font-size: 9px">{{ $item['Amount'] }}</span>
        @endif
    </div>
    <?php if ($item) : ?>
    <div class="info">
        @include('ranking.character.partials.inventory.item-blues-whites', ['item' => $item['ItemInfo']])
    </div>
    <?php endif; ?>
    <div class="clearfix"></div>
</div>
