@extends('layouts.app')
@section('title', __('Profile'))

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tbody>
                            @if($user->tbUser->shardUser->isEmpty())
                                {{ __('No Characters') }}
                            @else
                                @foreach($user->tbUser->shardUser as $value)
                                    <tr>
                                        <th scope="row">{{ __('Character Name:') }}</th>
                                        <td>
                                            @if($value->RefObjID > 2000)
                                                <img src="{{ asset(config('global.ranking.race')[1]['icon']) }}" width="16" height="16" alt=""/>
                                            @else
                                                <img src="{{ asset(config('global.ranking.race')[0]['icon']) }}" width="16" height="16" alt=""/>
                                            @endif
                                            <a href="{{ route('ranking.character.view', ['name' => $value->CharName16]) }}" class="text-decoration-none">{{ $value->CharName16 }}</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
