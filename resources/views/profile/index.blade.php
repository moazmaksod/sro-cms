@extends('layouts.app')
@section('title', __('Profile'))

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tbody>
                        <tr>
                            <th scope="row">{{ __('Username') }}</th>
                            <td>{{ Auth::user()->username }}</td>
                        </tr>
                        <tr>
                            <th scope="row">{{ __('Email') }}</th>
                            <td>{{ Auth::user()->email }}</td>
                        </tr>
                        <tr>
                            <th scope="row">{{ __('Silk') }}</th>
                            <td>{{ Auth::user()->getMuUser->getJCash->Silk ?? 0 }}</td>
                        </tr>
                        <tr>
                            <th scope="row">{{ __('Premium Silk') }}</th>
                            <td>{{ Auth::user()->getMuUser->getJCash->PremiumSilk ?? 0 }}</td>
                        </tr>
                        <tr>
                            <th scope="row">{{ __('Month Usage') }}</th>
                            <td>{{ Auth::user()->getMuUser->getJCash->MonthUsage ?? 0 }}</td>
                        </tr>
                        <tr>
                            <th scope="row">{{ __('3Month Usage') }}</th>
                            <td>{{ Auth::user()->getMuUser->getJCash->ThreeMonthUsage ?? 0 }}</td>
                        </tr>
                        <tr>
                            <th scope="row">{{ __('VIP') }}</th>
                            <td>
                                @isset(Auth::user()->getMuUser->getVipLevel->VIPUserType)
                                    <img src="{{ asset(config('global.ranking.vip_level.level')[Auth::user()->getMuUser->getVipLevel->VIPLv]['icon']) }}" width="24" height="24" alt="">
                                    <span>{{ config('global.ranking.vip_level.level')[Auth::user()->getMuUser->getVipLevel->VIPLv]['name'] }}</span>
                                @else
                                    <span>{{ __('None') }}</span>
                                @endisset
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
