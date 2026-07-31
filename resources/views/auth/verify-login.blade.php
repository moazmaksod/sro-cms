@extends('layouts.guest')
@section('title', __('Verification Code'))

@section('content')
    <section class="card">
        <div class="card-body">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <h2 class="mt-5">{{ __('Verification Code') }}</h2>

                        @if(session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <p class="text-muted mb-4">
                            {{ __('We sent a 6-digit verification code to your email.') }}
                        </p>

                        <form method="POST" action="{{ route('login.verify') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="code" class="form-label">{{ __('Verification Code') }}</label>
                                <input id="code" type="text" class="form-control @error('code') is-invalid @enderror" name="code" value="{{ old('code') }}" required autofocus placeholder="Enter 6-digit code">

                                @error('code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="mb-0">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Verify') }}
                                </button>

                                <button type="submit" form="verify_resend" class="btn btn-link">
                                    {{ __('Resend code') }}
                                </button>
                            </div>
                        </form>
                        <form method="POST" id="verify_resend" action="{{ route('login.resend') }}">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
