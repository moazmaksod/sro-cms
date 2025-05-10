@extends('layouts.app')
@section('title', __('MaxiCard'))

@section('content')
    <div class="container">
        <div class="row">
            <div class="card">
                <div class="card-body">
                    <form method="post" action="{{ route('profile.donate.process', ['method' => 'maxicard']) }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="code" class="col-md-4 col-form-label text-md-end">{{ __('E-Pin Code') }}</label>

                            <div class="col-md-6">
                                <input id="code" type="text" class="form-control @error('code') is-invalid @enderror" name="code" required>

                                @error('code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('E-Pin Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="text" class="form-control @error('password') is-invalid @enderror" name="password" required>

                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row text-center mt-4">
            <div class="card">
                <div class="card-body row">
                    @forelse($data['package'] as $value)
                        <div class="col-md-3">
                            <div class="card mb-4 rounded-3 shadow-sm">
                                <div class="card-header py-3">
                                    <h4 class="my-0 fw-normal">{{ $value['name'] }}</h4>
                                </div>
                                <div class="card-body">
                                    <p><small>Pay {{ $value['price'] }} {{ $data['currency'] }} for {{ $value['value'] }} Silk</small></p>
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
