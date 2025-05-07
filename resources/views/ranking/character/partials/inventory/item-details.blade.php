<div class="sro-item-detail {{ $item['ItemInfo']['SoxType'] != 'Normal' ? 'sro-item-special' : '' }}">
    <div class="item" data-itemInfo="1">
        @if($item['ItemInfo']['SoxType'] != 'Normal')
        <img alt="" class="sro-item-special-seal" src="{{ asset('/images/seal.gif') }}" />
        @endif
        @if(file_exists(public_path($item['ImgPath'])))
        <img alt="" src="{{ asset(strtolower($item['ImgPath'])) }}">
        @else
        <img alt="" src="{{ asset('/images/sro/icon_default.png') }}">
        @endif
        @if($item['Amount'])
        <span class="amount">{{ $item['Amount'] }}</span>
        @endif
    </div>
    <?php if ($item) : ?>
    <div class="info">
        @include('ranking.character.partials.inventory.item-blues-whites', ['item' => $item['ItemInfo']])
    </div>
    <?php endif; ?>
    <div class="clearfix"></div>
</div>
