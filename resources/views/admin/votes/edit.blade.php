@extends('admin.layouts.app')
@section('title', __('Edit Vote'))

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">{{ __('Edit Vote') }}</h1>
        </div>

        <div class="alert alert-info w-50 m-auto my-4" role="alert">
            <strong>Pingback URL:</strong>
            <code>{{ route('postback') }}</code>
            <br>
            Please configure your vote site to use this URL for postbacks.
        </div>

        <form method="POST" action="{{ route('admin.votes.update', $vote) }}">
            @csrf
            @method('PUT')

            <div class="row mb-3">
                <label for="title" class="col-md-2 col-form-label text-md-end">{{ __('Title') }}</label>

                <div class="col-md-10">
                    <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title', $vote->title) }}" required>

                    @error('title')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="url" class="col-md-2 col-form-label text-md-end">{{ __('Vote URL') }}</label>

                <div class="col-md-10">
                    <input id="url" type="text" class="form-control @error('title') is-invalid @enderror" name="url" value="{{ old('url', $vote->url) }}" required>
                    <div class="form-text">Please do not change <code>{JID}</code>; this is filled automatically with the User JID.</div>

                    @error('url')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="site" class="col-md-2 col-form-label text-md-end">{{ __('Site Key (no space)') }}</label>

                <div class="col-md-10">
                    <input id="site" type="text" class="form-control @error('site') is-invalid @enderror" name="site" value="{{ old('site', $vote->site) }}" required>

                    @error('site')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="image" class="col-md-2 col-form-label text-md-end">{{ __('Image Url') }}</label>

                <div class="col-md-10">
                    <input id="image" type="text" class="form-control @error('image') is-invalid @enderror" name="image" value="{{ old('image', $vote->image) }}" required>

                    @error('image')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="ip" class="col-md-2 col-form-label text-md-end">{{ __('IPs (comma separated)') }}</label>

                <div class="col-md-10">
                    <input id="ip" type="text" class="form-control @error('ip') is-invalid @enderror" name="ip" value="{{ old('ip', $vote->ip) }}" required>
                    <div class="form-text">Whitelist postback IPs, example: <code>127.0.0.1, 127.0.0.2</code></div>

                    @error('ip')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="param" class="col-md-2 col-form-label text-md-end">{{ __('User Param') }}</label>

                <div class="col-md-10">
                    <input id="param" type="text" class="form-control @error('param') is-invalid @enderror" name="param" value="{{ old('param', $vote->param) }}" required>
                    <div class="form-text">The parameter name the vote site will send back (e.g. <code>user_id</code>, <code>username</code>, etc).</div>

                    @error('param')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="reward" class="col-md-2 col-form-label text-md-end">{{ __('Reward (silk)') }}</label>

                <div class="col-md-10">
                    <input id="reward" type="number" class="form-control @error('reward') is-invalid @enderror" name="reward" value="{{ old('reward', $vote->reward) }}" required>

                    @error('reward')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="timeout" class="col-md-2 col-form-label text-md-end">{{ __('Timeout (hours)') }}</label>

                <div class="col-md-10">
                    <input id="timeout" type="number" class="form-control @error('timeout') is-invalid @enderror" name="timeout" value="{{ old('timeout', $vote->timeout) }}" required>

                    @error('timeout')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="active" class="col-md-2 col-form-label text-md-end">{{ __('Active') }}</label>

                <div class="col-md-10">
                    <select class="form-select" name="active" aria-label="Default select example">
                        <option value="1" {{ $vote->active == 1 ? 'selected' : '' }}>Yes</option>
                        <option value="0" {{ $vote->active == 0 ? 'selected' : '' }}>No</option>
                    </select>

                    @error('active')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-0">
                <div class="col-md-10 offset-md-2">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Update Vote') }}
                    </button>

                    <a href="{{ route('admin.votes.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                </div>
            </div>
        </form>
    </div>
@endsection
