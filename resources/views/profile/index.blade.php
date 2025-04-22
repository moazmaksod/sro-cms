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
                            <td>{{ Auth::user()->muUser->getJCash()->Silk }}</td>
                        </tr>
                        <tr>
                            <th scope="row">{{ __('Premium Silk') }}</th>
                            <td>{{ Auth::user()->muUser->getJCash()->PremiumSilk }}</td>
                        </tr>
                        <tr>
                            <th scope="row">{{ __('Month Usage') }}</th>
                            <td>{{ Auth::user()->muUser->getJCash()->MonthUsage }}</td>
                        </tr>
                        <tr>
                            <th scope="row">{{ __('3Month Usage') }}</th>
                            <td>{{ Auth::user()->muUser->getJCash()->ThreeMonthUsage }}</td>
                        </tr>
                        <tr>
                            <th scope="row">{{ __('VIP') }}</th>
                            <td>
                                @isset(Auth::user()->muUser->muVIPInfo->VIPUserType)
                                    <img src="{{ asset(config('global.ranking.vip_level.level')[Auth::user()->muUser->muVIPInfo->VIPLv]['icon']) }}" width="24" height="24" alt="">
                                    <span>{{ config('global.ranking.vip_level.level')[Auth::user()->muUser->muVIPInfo->VIPLv]['name'] }}</span>
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
