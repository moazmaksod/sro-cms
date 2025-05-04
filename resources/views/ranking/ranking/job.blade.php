<div class="container">
    <div class="col-md-12">
        <div class="d-inline-block mb-4 mx-2">
            @foreach($config as $value)
                @if($value['enabled'])
                    <button class="btn btn-secondary ranking-main-button-job rounded-0 me-2 mb-2 btn-sm" data-link="{{ route($value['route']) }}">{{ __($value['name']) }}</button>
                @endif
            @endforeach
        </div>
    </div>

    <div class="col-md-12">
        <div id="content-replace-job">
            @include('ranking.ranking.job-all')
        </div>
    </div>
</div>

<script>
    jQuery('.ranking-main-button-job').click(function() {
        jQuery('.ranking-main-button-job').removeClass('active');
        paginatorAjax('#content-replace-job', jQuery(this).data('link'));
        jQuery(this).addClass('active');
    });
</script>
