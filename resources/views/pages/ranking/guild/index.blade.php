@extends('layouts.full')
@section('title', __('Ranking'))

@section('content')
    <section class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6">
                    <h2>
                        @if(isset($data->Crest))
                            <img src="{{ route('ranking.guild.crest', ['bin' => $data->Crest]) }}" alt="" width="32" height="32">
                        @endif
                        {{ $data->Name }}
                    </h2>
                    <p>{{ __('Foundation Date:') }} <span class="">{{ date('d-m-Y', strtotime($data->FoundationDate)) }}</span></p>
                </div>

                <div class="col-lg-6">
                    <div class="row">
                        <div class="col">
                            <h4>{{ $data->LeaderName }}</h4>
                            <p class="text-muted">{{ __('Leader') }}</p>
                        </div>
                        <div class="col">
                            <h4>{{ $data->ItemPoint }}</h4>
                            <p class="text-muted">{{ __('Item Points') }}</p>
                        </div>
                        <div class="col">
                            <h4>{{ $data->Lvl }}</h4>
                            <p class="text-muted">{{ __('Level') }}</p>
                        </div>
                        <div class="col">
                            <h4>{{ $data->TotalMembers }}</h4>
                            <p class="text-muted">{{ __('Members') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                @include('pages.ranking.guild.partials.guild-members')
            </div>
            <div class="mt-4">
                @include('pages.ranking.guild.partials.guild-alliances')
            </div>
        </div>
    </section>
@endsection
