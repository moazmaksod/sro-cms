@extends('layouts.full')
@section('title', __('Ranking'))

@section('content')
    <section class="card">
        <div class="card-body">
            <div class="d-block text-center my-4">
                @foreach($config as $item)
                    @if($item->enabled)
                        <button class="btn btn-primary mb-1 {{ $loop->first ? 'active' : '' }}" data-link="{{ is_array($item->route)? route($item->route['name'], $item->route['params'] ?? []): route($item->route) }}">
                            {{ __($item->name) }}
                        </button>
                    @endif
                @endforeach
            </div>
            <div id="content-ranking">
                @if(request()->filled('search') || request()->filled('type'))
                    @if($type == 'guild')
                        @include('pages.ranking.ranking.guild')
                    @else
                        @include('pages.ranking.ranking.player')
                    @endif
                @else
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('[data-link]').forEach(function(btn) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    var link = this.dataset.link;

                    document.querySelectorAll('[data-link]').forEach(function(b) { b.classList.remove('active'); });
                    this.classList.add('active');

                    document.getElementById('content-ranking').innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';

                    fetch(link)
                        .then(function(res) {
                            if (!res.ok) throw new Error('Failed');
                            return res.text();
                        })
                        .then(function(html) {
                            document.getElementById('content-ranking').innerHTML = html;
                        })
                        .catch(function() {
                            document.getElementById('content-ranking').innerHTML = '<div class="alert alert-danger text-center">Failed to load ranking.</div>';
                        });
                });
            });

            var params = new URLSearchParams(window.location.search);
            var hasRankingQuery = params.has('type') || params.has('search');

            if (!hasRankingQuery) {
                var defaultButton = document.querySelector('[data-link]');
                if (defaultButton) {
                    defaultButton.click();
                } else {
                    document.getElementById('content-ranking').innerHTML = '<div class="alert alert-warning text-center">No ranking is enabled.</div>';
                }
            }
        });
    </script>
@endpush
