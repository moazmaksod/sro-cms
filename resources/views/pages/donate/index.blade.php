@extends('layouts.app')
@section('title', __('Donate'))

@section('sidebar')
    @include('partials.logged-in')
@stop

@section('content')
    <section class="card">
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if(collect($data)->filter(fn ($row) => is_array($row) && !empty($row['enabled']))->isNotEmpty())
                <div class="row g-4">
                    <div class="col-12">
                        <p>Select Payment Method</p>
                        <div class="row g-2 justify-content-center">
                            @foreach(collect($data)->filter(fn ($row) => is_array($row) && !empty($row['enabled'])) as $key => $row)
                                <div class="col">
                                    <div class="card h-100 {{ $loop->first ? 'selected' : '' }}" role="button" data-method="{{ $key }}">
                                        <img src="{{ asset($row['image']) }}" class="card-img-top object-fit-contain p-2" height="50" alt="{{ $row['name'] }}">
                                        <div class="card-body text-center p-2">
                                            <strong>{{ $row['name'] }}</strong>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-12">
                        <p>Select Package</p>
                        <div id="content-donate">
                            @foreach(collect($data)->filter(fn ($row) => is_array($row) && !empty($row['enabled'])) as $key => $row)
                                @include('pages.donate.' . $key, ['data' => $data[$key]])
                                @break
                            @endforeach
                        </div>
                    </div>

                    <div class="col-12" id="details-section">
                        <p>Order Details</p>
                        <div id="content-donate-details">
                            <form id="donate-form" method="POST">
                                @csrf
                                <input type="hidden" name="price" value="0">
                                <hr>
                                <p class="package-name text-muted mb-0 mt-2">Select a package</p>
                                <p class="package-price mb-0">Total amount: 0 USD</p>
                                <hr>
                                <button type="submit" class="btn w-100 btn-primary" disabled>{{ __('Buy Now') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            @else
                <div class="alert alert-info mb-0 text-center">
                    {{ __('All donation methods are currently disabled.') }}
                </div>
            @endif
        </div>
    </section>
@endsection
@push('styles')
    <style>
        .card[data-method].selected, .card[data-price].selected {
            border: 1px solid #0d6efd;
        }
        .card[data-method]:hover, #content-donate .card:hover {
            border: 1px solid #0d6efd;
            cursor: pointer;
        }
    </style>
@endpush
@push('scripts')
    <script>
        var FORM_ACTION = "{{ route('account.donate.process', ['method' => '_METHOD_']) }}";

        document.addEventListener('DOMContentLoaded', function () {
            var donate = document.getElementById('content-donate');
            var details = document.getElementById('content-donate-details');
            var detailsSection = document.getElementById('details-section');

            function showDetails(show) {
                detailsSection.classList.toggle('d-none', !show);
            }

            function q(sel, ctx) { return (ctx || document).querySelector(sel); }
            function qa(sel, ctx) { return (ctx || document).querySelectorAll(sel); }

            function setBtn(disabled, text) {
                var btn = q('button[type=submit]', details);
                if (!btn) return;
                btn.disabled = disabled;
                if (text) btn.textContent = text;
            }

            function loadPackages(method) {
                donate.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';

                fetch(FORM_ACTION.replace('_METHOD_', method).replace('/process', ''), {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        credentials: 'same-origin'
                    })
                    .then(function (r) { if (!r.ok) throw new Error(); return r.text(); })
                    .then(function (html) {
                        donate.innerHTML = html;
                        q('input[name=price]', details).value = 0;
                        q('.package-name', details).textContent = 'Select a package';
                        q('.package-price', details).textContent = 'Total amount: 0 USD';

                        if (['maxicard', 'hipocard', 'custom'].includes(method)) {
                            setBtn(true, 'Not Available');
                        } else {
                            setBtn(true, 'Buy Now');
                        }
                    })
                    .catch(function () {
                        donate.innerHTML = '<div class="alert alert-danger">Failed to load package options.</div>';
                    });
            }

            qa('[data-method]').forEach(function (card) {
                card.addEventListener('click', function () {
                    var method = this.dataset.method;
                    if (location.protocol === 'https:' && method.startsWith('http:')) {
                        method = method.replace(/^http:/, 'https:');
                    }

                    qa('[data-method]').forEach(function (c) { c.classList.remove('selected'); });
                    this.classList.add('selected');

                    showDetails(!['maxicard', 'hipocard', 'custom'].includes(method));
                    q('form', details).action = FORM_ACTION.replace('_METHOD_', method);
                    loadPackages(method);
                });
            });

            donate.addEventListener('click', function (e) {
                var card = e.target.closest('.card');
                if (!card) return;

                var method = q('[data-method].selected');
                method = method ? method.dataset.method : null;

                qa('#content-donate .card').forEach(function (c) { c.classList.remove('selected'); });
                card.classList.add('selected');

                q('input[name=price]', details).value = card.dataset.price;

                if (['maxicard', 'hipocard'].includes(method)) {
                    setBtn(true, 'Not Available');
                } else {
                    setBtn(false, 'Buy Now');
                }

                q('.package-name', details).textContent = 'Package: ' + card.dataset.name;
                q('.package-price', details).textContent = 'Total amount: ' + card.dataset.price + ' ' + card.dataset.currency;
            });
        });
    </script>
@endpush
