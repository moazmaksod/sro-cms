@extends('layouts.app')
@section('title', __('Profile'))

@section('content')
    <div class="container">
        <h3 class="">Characters:</h3>
        <div class="row">
            @if($user->tbUser->shardUser->isEmpty())
                <div class="alert alert-danger text-center" role="alert">
                    {{ __('No Characters') }}
                </div>
            @else
                @foreach($user->tbUser->shardUser as $value)
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body text-center">
                                <div class="d-flex overflow-hidden align-items-center justify-content-center mb-2">
                                    <img class="object-fit-cover rounded border" src="{{ asset(config('global.ranking.character')[$value->RefObjID]) }}" width="100" height="100" alt=""/>
                                </div>

                                @if($value->RefObjID > 2000)
                                    <img src="{{ asset(config('global.ranking.race')[1]['icon']) }}" width="16" height="16" alt=""/>
                                @else
                                    <img src="{{ asset(config('global.ranking.race')[0]['icon']) }}" width="16" height="16" alt=""/>
                                @endif
                                <a href="{{ route('ranking.character.view', ['name' => $value->CharName16]) }}" class="text-decoration-none">{{ $value->CharName16 }}</a>
                                <p>{{ __('Lv:') }} {{ $value->CurLevel }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <h3 class="mt-4">Informaion:</h3>
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
