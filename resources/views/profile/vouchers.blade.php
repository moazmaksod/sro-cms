@extends('layouts.app')
@section('title', __('Redeem Voucher'))

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">{{ __('Redeem Voucher') }}</div>

            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form method="POST" action="{{ route('profile.vouchers.redeem') }}">
                    @csrf

                    <div class="row mb-3">
                        <label for="code" class="col-md-4 col-form-label text-md-end">
                            {{ __('Voucher Code:') }}
                        </label>

                        <div class="col-md-6">
                            <input id="code" type="text" class="form-control @error('code') is-invalid @enderror" name="code" placeholder="Enter Voucher Code" required>

                            @error('code')
                                <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Redeem') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
