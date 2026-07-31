<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="auto">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('global.site_name', 'iSRO CMS v2') }} - @yield('title')</title>
    <meta name="description" content="{{ config('global.site_desc', 'Description') }}">
    <link rel="shortcut icon" href="{{ asset(config('global.site_favicon', 'images/favicon.ico')) }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://getbootstrap.com/docs/5.3/examples/dashboard/dashboard.css" rel="stylesheet">
    <style>
        svg { width: 1em; height: 1em; }
        .bi { fill: currentColor; }
        @media (min-width: 768px) {
            .sidebar .offcanvas-md {
                height: calc(100vh - 48px);
                overflow-y: auto;
            }
        }
    </style>
    @stack('styles')
</head>
<body data-bs-theme="{{ config('global.dark_mode', 'dark') }}">

@include('admin.layouts.header')

<div class="container-fluid">
    <div class="row">
        <div class="sidebar border border-right col-md-3 col-lg-2 p-0 bg-body-tertiary">
            @include('admin.layouts.sidebar')
        </div>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            @yield('content')
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
<script src="https://getbootstrap.com/docs/5.3/assets/js/color-modes.js"></script>

@stack('scripts')
</body>
</html>
