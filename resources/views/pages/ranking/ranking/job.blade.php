<div class="d-inline-block mb-4">
    @foreach($config as $item)
        @if($item->enabled)
            <button class="btn btn-secondary btn-sm {{ $loop->first ? 'active' : '' }}" data-link-job="{{ route($item->route) }}">
                {{ __($item->name) }}
            </button>
        @endif
    @endforeach
</div>

<div id="content-ranking-job">
    @include('pages.ranking.ranking.job-all')
</div>
