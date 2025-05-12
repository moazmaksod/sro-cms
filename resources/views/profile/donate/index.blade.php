@extends('layouts.app')
@section('title', __('Donate'))

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-4">
                        <p>Select Payment Method</p>
                        @php $i = 1; @endphp
                        @foreach($data as $key => $method)
                            @if($method['enabled'])
                                <div class="card mb-2 {{ $i == 1 ? 'selected' : '' }}" role="button" data-method="{{ $key }}" style="">
                                    <img src="{{ asset($method['image']) }}" class="card-img-top object-fit-contain p-2" height="50" alt="{{ $method['name'] }}">
                                    <div class="card-body text-center">
                                        <strong>{{ $method['name'] }}</strong><br>
                                        <small class="text-muted">{{ __('Currency:') }} {{ $method['currency'] }}</small>
                                    </div>
                                </div>
                                @php $i++; @endphp
                            @endif
                        @endforeach
                    </div>

                    <div class="col-md-8">
                        <p>Select Package</p>
                        <div id="package-section">
                            @php
                                $method = null;
                                foreach ($data as $key => $value) {
                                    if ($value['enabled']) {
                                        $method = $key;
                                        break;
                                    }
                                }
                            @endphp
                            @include('profile.donate.' . $method, ['data' => $data[$method]])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('styles')
    <style>
        .card[data-method].selected {
            border: 1px solid #0d6efd;
            box-shadow: 0 0 10px rgba(13, 110, 253, 0.4);
        }
        .card[data-method]:hover, #package-section .card:hover {
            border: 1px solid #0d6efd;
            box-shadow: 0 0 8px rgba(13, 110, 253, 0.3);
            cursor: pointer;
        }
    </style>
@endpush
@push('scripts')
    <script>
        $(document).ready(function () {
            $('[data-method]').on('click', function () {
                const method = $(this).data('method');

                $('[data-method]').removeClass('selected'); // remove from all
                $(this).addClass('selected'); // add to clicked

                $.get(`/profile/donate/${method}`, function (res) {
                    $('#package-section').html(res);
                }).fail(function () {
                    $('#package-section').html('<div class="alert alert-danger">Failed to load package options.</div>');
                });
            });
        });
    </script>
@endpush
