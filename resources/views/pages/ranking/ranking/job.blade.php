<div class="container">
    <div class="col-md-12">
        <div class="d-inline-block mb-4 mx-2">
            @foreach($config as $item)
                @if($item->enabled)
                    <button class="btn btn-secondary rounded-0 me-2 mb-2 btn-sm" data-link-job="{{ route($item->route) }}">
                        {{ __($item->name) }}
                    </button>
                @endif
            @endforeach
        </div>
    </div>

    <div class="col-md-12">
        <div id="content-ranking-job">
            @include('ranking.ranking.job-all')
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-link-job]').forEach(function(btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            var link = this.dataset.linkJob;

            document.querySelectorAll('[data-link-job]').forEach(function(b) { b.classList.remove('selected'); });
            this.classList.add('selected');

            document.getElementById('content-ranking-job').innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';

            fetch(link)
                .then(function(res) {
                    if (!res.ok) throw new Error('Failed');
                    return res.text();
                })
                .then(function(html) {
                    document.getElementById('content-ranking-job').innerHTML = html;
                })
                .catch(function() {
                    document.getElementById('content-ranking-job').innerHTML = '<div class="alert alert-danger">Failed to load Job Ranking.</div>';
                });
        });
    });
});
</script>
