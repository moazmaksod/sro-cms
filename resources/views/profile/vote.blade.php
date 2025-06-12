@extends('layouts.app')
@section('title', __('Vote Sites'))

@section('sidebar')
    @include('profile.sidebar')
@stop

@section('content')
    <div class="container">
        <div class="row">
            @foreach($data as $value)
                <div class="col-md-3 mb-4">
                    <div class="card @if($value->active) border-primary @endif">
                        <div class="card-body text-center">
                            <div class="d-flex overflow-hidden align-items-center justify-content-center mb-2">
                                <img class="object-fit-cover rounded border" src="{{ $value->image }}" alt="" style="min-width: 90px; min-height: 50px;"/>
                            </div>
                            <p class="text-white mb-0">{{ $value->title }}</p>
                            <p class="text-muted mb-0">{{ __('Reward:') }} {{ $value->reward }} Silk</p>
                            <p class="text-muted mb-2">{{ __('Timeout:') }} {{ $value->timeout }} Hours</p>

                            @if($value->active)
                                <a href="{{ route('profile.vote.voting', $value->id) }}" target="_blank" class="btn btn-primary text-decoration-none">Vote Now</a>
                            @else
                                <button class="btn btn-outline-secondary" disabled>Vote Now</button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
