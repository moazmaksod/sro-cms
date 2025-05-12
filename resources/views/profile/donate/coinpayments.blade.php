@extends('layouts.app')
@section('title', __('NowPayments'))

@section('content')
    <div class="container">
        <div class="row text-center">
            <div class="card">
                <div class="card-body row">
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

                    @forelse($data['package'] as $value)
                        <div class="col-md-3">
                            <div class="card mb-4 rounded-3 shadow-sm">
                                <div class="card-header py-3">
                                    <h4 class="my-0 fw-normal">{{ $value['name'] }}</h4>
                                </div>
                                <div class="card-body">
                                    <p><small>Pay {{ $value['price'] }} {{ $data['currency'] }} for {{ $value['value'] }} Silk</small></p>

                                    <form action="{{ route('profile.donate.process', ['method' => $data['route']]) }}" method="POST">
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
@endsection
