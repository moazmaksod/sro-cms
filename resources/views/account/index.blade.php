@extends('layouts.app')
@section('title', __('Account Info'))

@section('sidebar')
    @include('account.sidebar')
@stop

@section('content')
    <section class="card">
        <div class="card-body">
            <h3 class="mt-0">{{ __('Characters') }}</h3>
            <div class="row">
                @if(!auth()->user()->tbUser || auth()->user()->tbUser->shardUser->isEmpty())
                    <div class="alert alert-danger text-center" role="alert">
                        {{ __('No Characters Found!') }}
                    </div>
                @else
                    @foreach(auth()->user()->tbUser->shardUser as $row)
                        <div class="col-md-3">
                            <div class="card">
                                <div class="card-body text-center">
                                    @if(config('global.server.version') === 'vSRO')
                                        <img class="d-block m-auto object-fit-cover rounded border" src="{{ asset('images/character/'.config('ranking.character_image_vsro')[$row->RefObjID]) }}" width="100" height="100" alt=""/>
                                    @else
                                        <img class="d-block m-auto object-fit-cover rounded border" src="{{ asset('images/character/'.config('ranking.character_image')[$row->RefObjID]) }}" width="100" height="100" alt=""/>
                                    @endif
                                    @if($row->RefObjID > 2000)
                                        <img src="{{ asset(config('ranking.character_race')[1]['image']) }}" width="16" height="16" alt=""/>
                                    @else
                                        <img src="{{ asset(config('ranking.character_race')[0]['image']) }}" width="16" height="16" alt=""/>
                                    @endif
                                    <a href="{{ route('ranking.character.view', ['name' => $row->CharName16]) }}" class="text-decoration-none">{{ $row->CharName16 }}</a>
                                    <p>{{ __('Lv:') }} {{ $row->CurLevel }}</p>
                                    <p>{{ __('Gold:') }} {{ number_format($row->RemainGold , 0, ',')}}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <h3 class="mt-4">{{ __('Information') }}</h3>
            <div class="table-responsive">
                <table class="table">
                    @if(!auth()->user()->tbUser)
                        <tr>
                            <td class="text-center">{{ __('Cannot load your account information!') }}</td>
                        </tr>
                    @else
                        @if(config('global.server.version') === 'vSRO')
                            <tbody>
                            <tr>
                                <th scope="row">{{ __('Username') }}</th>
                                <td>{{ auth()->user()->tbUser->StrUserID }}</td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Email') }}</th>
                                <td>{{ auth()->user()->tbUser->Email }}</td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Silk') }}</th>
                                <td>{{ auth()->user()->tbUser->getSilk->silk_own ?? 0 }}</td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Gift Silk') }}</th>
                                <td>{{ auth()->user()->tbUser->getSilk->silk_gift ?? 0 }}</td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Point Silk') }}</th>
                                <td>{{ auth()->user()->tbUser->getSilk->silk_point ?? 0 }}</td>
                            </tr>
                            </tbody>
                        @else
                            <tbody>
                            <tr>
                                <th scope="row">{{ __('Username') }}</th>
                                <td>{{ auth()->user()->tbUser->StrUserID }}</td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Email') }}</th>
                                <td>{{ auth()->user()->muUser->muEmail->EmailAddr }}</td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Premium Silk') }}</th>
                                <td>{{ auth()->user()->muUser->getSilk->PremiumSilk ?? 0 }}</td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Month Usage') }}</th>
                                <td>{{ auth()->user()->muUser->getSilkUsage->MonthUsage ?? 0 }}</td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('3Month Usage') }}</th>
                                <td>{{ auth()->user()->muUser->getSilkUsage->ThreeMonthUsage ?? 0 }}</td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('Silk') }}</th>
                                <td>{{ auth()->user()->muUser->getSilk->Silk ?? 0 }}</td>
                            </tr>
                            <tr>
                                <th scope="row">{{ __('VIP') }}</th>
                                <td>
                                    @isset(auth()->user()->muUser->muVIPInfo->VIPUserType)
                                        <img src="{{ asset(config('ranking.vip_level')['level'][auth()->user()->muUser->muVIPInfo->VIPLv]['image']) }}" width="24" height="24" alt="">
                                        <span>{{ config('ranking.vip_level')['level'][auth()->user()->muUser->muVIPInfo->VIPLv]['name'] }}</span>
                                    @else
                                        <span>{{ __('None') }}</span>
                                    @endisset
                                </td>
                            </tr>
                            </tbody>
                        @endif
                    @endif
                </table>
            </div>
        </div>
    </section>
@endsection
