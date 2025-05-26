@extends('layouts.full')
@section('title', __('Ranking'))

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="col-md-12">
                    <div class="d-inline-block text-center my-4 mx-3">
                        @foreach($config as $value)
                            @if($value['enabled'])
                                <button class="btn btn-primary rounded-0 me-2 mb-2 {{ $value['route'] === 'ranking.player' ? 'selected' : '' }}" data-link="{{ is_array($value['route'])? route($value['route']['name'], $value['route']['params'] ?? []): route($value['route']) }}">{{ __($value['name']) }}</button>
                            @endif
                        @endforeach
                    </div>
                </div>

                <div class="col-md-12">
                    <div id="content-ranking">
                        @if($type == 'guild')
                            @include('ranking.ranking.guild')
                        @else
                            @include('ranking.ranking.player')
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            $('[data-link]').on('click', function (e) {
                e.preventDefault();
                const link = $(this).data('link');

                $('[data-link]').removeClass('selected');
                $(this).addClass('selected');

                $.get(`${link}`, function (res) {
                    $('#content-ranking').html(res);
                }).fail(function () {
                    $('#content-ranking').html('<div class="alert alert-danger">Failed to load Ranking.</div>');
                });
            });
        });
    </script>
@endpush
