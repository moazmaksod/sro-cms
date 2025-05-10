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

                <div class="accordion" id="accordionDonate">
                    @php $i = 1 @endphp
                    @forelse($data as $key => $values)
                        @if($values['enabled'])
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button {{ $i == 1 ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $key }}" aria-expanded="{{ $i == 1 ? 'true' : 'false' }} " aria-controls="{{ $key }}">
                                        <img src="{{ asset($values['image']) }}" alt="" width="150" height="" class="">
                                        <div class="d-flex mx-3">
                                            <div>
                                                <h6 class="mb-0">{{ $values['name'] }}</h6>
                                                <p class="mb-0 opacity-75">{{ __('Currency:') }} {{ $values['currency'] }}</p>
                                            </div>
                                        </div>
                                    </button>
                                </h2>
                                <div id="{{ $key }}" class="accordion-collapse collapse {{ $i == 1 ? 'show' : '' }}" data-bs-parent="#accordionDonate">
                                    <div class="accordion-body">
                                        <div class="row text-center">
                                            @forelse($values['package'] as $value)
                                                <div class="col-md-3">
                                                    <div class="card mb-4 rounded-3 shadow-sm">
                                                        <div class="card-header py-3">
                                                            <h4 class="my-0 fw-normal">{{ $value['name'] }}</h4>
                                                        </div>
                                                        <div class="card-body">
                                                            <p><small>Pay {{ $value['price'] }} {{ $values['currency'] }} for {{ $value['value'] }} Silk</small></p>

                                                            <form action="{{ route('profile.donate.process', ['method' => $values['route']]) }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="price" value="{{ $value['price'] }}">
                                                                <button type="submit" class="btn btn-primary">{{ __('Buy Now') }}</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="alert alert-danger" role="alert">
                                                    {{ __('No Packages Available!') }}
                                                </div>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                            </div>
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
