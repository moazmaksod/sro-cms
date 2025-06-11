@extends('layouts.app')
@section('title', __('Profile'))

@section('sidebar')
    @include('profile.sidebar')
@stop

@section('content')
    <div class="container">
        <div class="mb-4">
            @include('profile.partials.update-profile-information-form')
        </div>
        <div class="mb-4">
            @include('profile.partials.update-password-form')
        </div>
        <div>
            {{--@include('profile.partials.delete-user-form')--}}
        </div>
        @if(config('global.server.version') !== 'vSRO')
            <div class="mb-4">
                @include('profile.partials.reset-secondary-password')
            </div>
        @endif
        <div class="mb-4">
            @include('profile.partials.general-settings')
        </div>
    </div>
@endsection
