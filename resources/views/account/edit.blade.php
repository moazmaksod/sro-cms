@extends('layouts.app')
@section('title', __('Profile'))

@section('sidebar')
    @include('account.sidebar')
@stop

@section('content')
    <div class="container">
        <div class="mb-4">
            @include('account.partials.update-profile-information-form')
        </div>
        <div class="mb-4">
            @include('account.partials.update-password-form')
        </div>
        <div>
            {{--@include('account.partials.delete-user-form')--}}
        </div>
        @if(config('global.server.version') !== 'vSRO')
            <div class="mb-4">
                @include('account.partials.reset-secondary-password')
            </div>
        @endif
        <div class="mb-4">
            @include('account.partials.general-settings')
        </div>
    </div>
@endsection
