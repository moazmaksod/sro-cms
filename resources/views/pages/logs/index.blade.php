@extends('layouts.full')
@section('title', __('History'))

@section('content')
    <div class="container">
        <div class="card border-0">
            <div class="card-body p-0">
                <h1 class="mb-4 text-center">Server Stats</h1>
                <hr>

                <div class="row">
                    <div class="row justify-content-center mb-4">
                        <div class="col-md-4 mb-3">
                            @include('partials.server-status')
                            @include('partials.server-info')
                        </div>
                        @if(config("widgets.fortress_war.enabled"))
                            <div class="col-md-4 mb-3">
                                @include('partials.fortress-war')
                                <a class="btn btn-primary w-100" href="{{ route('logs.fortress') }}">{{ __('More') }}</a>
                            </div>
                        @endif
                        @if(config("widgets.event_schedule.enabled"))
                            <div class="col-md-4 mb-3">
                                @include('partials.event-schedule')
                                <a class="btn btn-primary w-100" href="{{ route('logs.schedule') }}">{{ __('More') }}</a>
                            </div>
                        @endif
                    </div>

                    <h1 class="mb-4 text-center">Server History</h1>
                    <hr>

                    <div class="row justify-content-center mb-4">
                        @if(config("widgets.unique_history.enabled"))
                            <div class="col-md-6 mb-3">
                                @include('partials.unique-history')
                                <a class="btn btn-primary w-100" href="{{ route('logs.unique') }}">{{ __('More') }}</a>
                                <a class="btn btn-primary mt-2 w-100" href="{{ route('logs.unique-advanced') }}">{{ __('Advanced Tracker') }}</a>
                            </div>
                        @endif
                        @if(config("widgets.global_history.enabled"))
                            <div class="col-md-6 mb-3">
                                @include('partials.global-history')
                                <a class="btn btn-primary w-100" href="{{ route('logs.global') }}">{{ __('More') }}</a>
                            </div>
                        @endif
                    </div>

                    <h1 class="mb-4 text-center">Top Ranking</h1>
                    <hr>

                    <div class="row justify-content-center mb-4">
                        @if(config("widgets.top_player.enabled"))
                            <div class="col-md-6 mb-3">
                                @include('partials.top-player')
                                <a class="btn btn-primary w-100" href="{{ route('ranking') }}">{{ __('More') }}</a>
                            </div>
                        @endif
                        @if(config("widgets.top_guild.enabled"))
                            <div class="col-md-6 mb-3">
                                @include('partials.top-guild')
                                <a class="btn btn-primary w-100" href="{{ route('ranking') }}">{{ __('More') }}</a>
                            </div>
                        @endif
                    </div>

                    <h1 class="mb-4 text-center">Item Logs</h1>
                    <hr>

                    <div class="row justify-content-center mb-4">
                        @if(config("widgets.item_plus.enabled"))
                            <div class="col-md-6 mb-3">
                                @include('partials.item-plus')
                                <a class="btn btn-primary w-100" href="{{ route('logs.item-plus') }}">{{ __('More') }}</a>
                            </div>
                        @endif
                        @if(config("widgets.item_drop.enabled"))
                            <div class="col-md-6 mb-3">
                                @include('partials.item-drop')
                                <a class="btn btn-primary w-100" href="{{ route('logs.item-drop') }}">{{ __('More') }}</a>
                            </div>
                        @endif
                    </div>

                    <h1 class="mb-4 text-center">Pvp Logs</h1>
                    <hr>

                    <div class="row justify-content-center mb-4">
                        @if(config("widgets.pvp_kill.enabled"))
                            <div class="col-md-6 mb-3">
                                @include('partials.pvp-kill')
                                <a class="btn btn-primary w-100" href="{{ route('logs.pvp-kill') }}">{{ __('More') }}</a>
                            </div>
                        @endif
                        @if(config("widgets.job_kill.enabled"))
                            <div class="col-md-6 mb-3">
                                @include('partials.job-kill')
                                <a class="btn btn-primary w-100" href="{{ route('logs.job-kill') }}">{{ __('More') }}</a>
                            </div>
                        @endif
                    </div>

                    <h1 class="mb-4 text-center">Discord Server</h1>
                    <hr>

                    <div class="mb-4">
                        @include('partials.discord')
                        <a class="btn btn-primary w-100" href="https://discord.gg/mix-store">{{ __('Join') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
