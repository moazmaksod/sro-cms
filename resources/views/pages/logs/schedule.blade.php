@extends('layouts.full')
@section('title', __('Event Times'))

@section('content')
    <div class="container">
        <div class="card border-0">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('Event Name') }}</th>
                                <th>{{ __('Remaining Time') }}</th>
                                <th>{{ __('Duration') }}</th>
                                <th>{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $row)
                                <tr>
                                    <td>{{ $row->idx }}</td>
                                    <td>{{ $row->name }}</td>
                                    <td>
                                        <span class="timerCountdown" id="idTimeCountdown_{{ $row->idx }}" data-time="{{ $row->timestamp }}"></span>
                                    </td>
                                    <td>{{ Carbon\CarbonInterval::seconds($row->duration)->cascade()->forHumans() }}</td>
                                    <td>
                                        @if($row->status)
                                            <span class="text-success">{{ __('Active') }}</span>
                                        @else
                                            <span class="text-warning">{{ __('Planned') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')

@endpush
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var els = document.querySelectorAll('.timerCountdown');
            if (!els.length) return;

            var events = {};
            els.forEach(function(el) {
                events[el.id] = Number(el.dataset.time);
            });

            function pad(n) { return n < 10 ? '0' + n : '' + n; }

            function tick() {
                var now = Math.round(Date.now() / 1000);
                Object.keys(events).forEach(function(id) {
                    var el = document.getElementById(id);
                    if (!el) return;
                    var left = events[id] - now;
                    if (left <= 0) { el.textContent = '00:00:00'; return; }
                    var d = Math.floor(left / 86400);
                    var h = Math.floor((left % 86400) / 3600);
                    var m = Math.floor((left % 3600) / 60);
                    var s = left % 60;
                    el.textContent = d > 0 ? d + 'd ' + pad(h) + ':' + pad(m) + ':' + pad(s) : pad(h) + ':' + pad(m) + ':' + pad(s);
                });
            }

            tick();
            setInterval(tick, 1000);
        });
    </script>
@endpush
