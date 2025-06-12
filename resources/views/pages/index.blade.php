@if(config('global.homepage.type') === 'landing')
    @include('pages.landing')
    @php exit; @endphp
@endif

@extends('layouts.app')
@section('title', __('Home'))

@section('hero')
    @include('partials.carousel')
@stop

@section('content')
    <div class="container">
        @forelse($data as $value)
            <div class="card mb-4">
                @if ($value->image)
                    <img src="{{ $value->image }}" class="card-img-top object-fit-cover" alt="..." style="height: 200px;">
                @endif
                <div class="card-header">
                    <a href="{{ route('pages.post.show', ['slug' => $value->slug]) }}" class="text-decoration-none">
                        <h5 class="card-title">{{ $value->title }}</h5>
                    </a>
                    <p class="card-text">
                        @switch($value->category)
                            @case('news')
                                <span class="badge text-bg-warning">{{ __('News') }}</span>
                                @break
                            @case('update')
                                <span class="badge text-bg-primary">{{ __('Update') }}</span>
                                @break
                            @case('event')
                                <span class="badge text-bg-success">{{ __('Event') }}</span>
                                @break
                            @default
                                <span class="badge text-bg-warning">{{ __('News') }}</span>
                        @endswitch

                        {{ __('Published on') }} {{ $value->published_at->format("M j, Y") }}
                    </p>
                </div>
                <div class="card-body">
                    {!! $value->content !!}
                </div>
            </div>
        @empty
            <div class="alert alert-danger text-center" role="alert">
                {{ __('No Posts Available!') }}
            </div>
       @endforelse
    </div>
@endsection
