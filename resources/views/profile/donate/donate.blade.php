@extends('layouts.app')
@section('title', __('Donate'))

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="list-group">
                    @php $i = 1 @endphp
                    @forelse($data as $key => $values)
                        @if($values['enabled'])
                        <a href="{{ route('profile.donate.show', ['method' => $values['route']]) }}" class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true">
                            <img src="{{ asset($values['image']) }}" alt="" width="150" height="" class="flex-shrink-0">
                            <div class="d-flex gap-2 w-100 justify-content-between">
                                <div>
                                    <h6 class="mb-0">{{ $values['name'] }}</h6>
                                    <p class="mb-0 opacity-75">{{ __('Currency:') }} {{ $values['currency'] }}</p>
                                </div>
                            </div>
                        </a>
                        @endif
                        @php $i++ @endphp
                    @empty
                        <div class="alert alert-danger" role="alert">
                            {{ __('No Methods Available!') }}
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection
