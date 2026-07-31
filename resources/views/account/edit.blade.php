@extends('layouts.app')
@section('title', __('Settings'))

@section('sidebar')
    @include('account.sidebar')
@stop

@section('content')
    <div class="mb-4">
        @include('account.partials.update-profile-information-form')
    </div>
    <div class="mb-4">
        @include('account.partials.update-password-form')
    </div>
    @if(config('global.server.version') !== 'vSRO')
        <div class="mb-4">
            @include('account.partials.reset-secondary-password')
        </div>
    @endif
    <div class="mb-4">
        @include('account.partials.general-settings')
    </div>
@endsection
