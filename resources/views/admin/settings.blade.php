@extends('admin.layouts.app')
@section('title', __('Settings'))

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Settings</h1>

            <div class="btn-toolbar mb-2 mb-md-0">
                <div class="btn-group me-2">
                    <form action="{{ route('admin.settings.clear-cache') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear all caches?')">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            Clear All Cache
                        </button>
                    </form>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf

            <div class="row mb-3">
                <label for="site_title" class="col-md-2 col-form-label text-md-end">{{ __('Site Title') }}</label>

                <div class="col-md-10">
                    <input id="site_title" type="text" class="form-control @error('site_title') is-invalid @enderror" name="site_title" value="{{ $settings['site_title'] ?? '' }}" placeholder="" required>

                    @error('site_title')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="site_desc" class="col-md-2 col-form-label text-md-end">{{ __('Site Description') }}</label>

                <div class="col-md-10">
                    <input id="site_desc" type="text" class="form-control @error('site_desc') is-invalid @enderror" name="site_desc" value="{{ $settings['site_desc'] ?? '' }}" placeholder="" required>

                    @error('site_desc')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="site_url" class="col-md-2 col-form-label text-md-end">{{ __('Site URL') }}</label>

                <div class="col-md-10">
                    <input id="site_url" type="text" class="form-control @error('site_url') is-invalid @enderror" name="site_url" value="{{ $settings['site_url'] ?? '' }}" placeholder="" required>

                    @error('site_url')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="site_favicon" class="col-md-2 col-form-label text-md-end">{{ __('Site Favicon') }}</label>

                <div class="col-md-10">
                    <input id="site_favicon" type="text" class="form-control @error('site_favicon') is-invalid @enderror" name="site_favicon" value="{{ $settings['site_favicon'] ?? '' }}" placeholder="" required>

                    @error('site_favicon')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="site_logo" class="col-md-2 col-form-label text-md-end">{{ __('Site Logo') }}</label>

                <div class="col-md-10">
                    <input id="site_logo" type="text" class="form-control @error('site_logo') is-invalid @enderror" name="site_logo" value="{{ $settings['site_logo'] ?? '' }}" placeholder="" required>

                    @error('site_logo')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="max_player" class="col-md-2 col-form-label text-md-end">{{ __('Max Online Player') }}</label>

                <div class="col-md-10">
                    <input id="max_player" type="number" class="form-control @error('max_player') is-invalid @enderror" name="max_player" value="{{ $settings['max_player'] ?? '' }}" placeholder="" required>

                    @error('max_player')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="fake_player" class="col-md-2 col-form-label text-md-end">{{ __('Add Fake Player') }}</label>

                <div class="col-md-10">
                    <input id="fake_player" type="number" class="form-control @error('fake_player') is-invalid @enderror" name="fake_player" value="{{ $settings['fake_player'] ?? '' }}" placeholder="" required>

                    @error('fake_player')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="max_level" class="col-md-2 col-form-label text-md-end">{{ __('Max Character Level') }}</label>

                <div class="col-md-10">
                    <input id="max_level" type="number" class="form-control @error('max_level') is-invalid @enderror" name="max_level" value="{{ $settings['max_level'] ?? '' }}" placeholder="" required>

                    @error('max_level')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="dark_mode" class="col-md-2 col-form-label text-md-end">{{ __('Dark Mode') }}</label>

                <div class="col-md-10">
                    <select class="form-select" name="dark_mode" aria-label="Default select example">
                        <option value="switch" {{ config('settings.dark_mode') == 'switch' ? 'selected' : '' }}>Switch</option>
                        <option value="light" {{ config('settings.dark_mode') == 'light' ? 'selected' : '' }}>Light</option>
                        <option value="dark" {{ config('settings.dark_mode') == 'dark' ? 'selected' : '' }}>Dark</option>
                    </select>

                    @error('dark_mode')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="default_locale" class="col-md-2 col-form-label text-md-end">{{ __('Default Language') }}</label>

                <div class="col-md-10">
                    <select class="form-select" name="default_locale" aria-label="Default select example">
                        <option value="switch" {{ config('settings.default_locale') == 'switch' ? 'selected' : '' }}>Switch</option>

                        @foreach(config('global.general.languages') as $key => $value)
                            <option value="{{ $key }}" {{ config('settings.default_locale') == $key ? 'selected' : '' }}>{{ $value['name'] }}</option>
                        @endforeach
                    </select>

                    @error('default_locale')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="theme" class="col-md-2 col-form-label text-md-end">{{ __('Theme') }}</label>

                <div class="col-md-10">
                    <select class="form-select" name="theme" aria-label="Default select example">
                        <option value="default" {{ config('settings.theme') == 'default' ? 'selected' : '' }}>Default</option>
                        @php
                            if (is_dir(resource_path('themes'))) {
                                foreach (scandir(resource_path('themes')) as $dir) {
                                    if ($dir !== '.' && $dir !== '..' && is_dir(resource_path('themes') . '/' . $dir)) {
                                        $themes[] = $dir;
                                    }
                                }
                            }
                        @endphp

                        @isset($themes)
                            @foreach($themes as $theme)
                                <option value="{{ $theme }}" {{ config('settings.theme') === $theme ? 'selected' : '' }}>
                                    {{ ucfirst($theme) }}
                                </option>
                            @endforeach
                        @endisset
                    </select>

                    @error('theme')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="timezone" class="col-md-2 col-form-label text-md-end">{{ __('Timezone') }}</label>

                <div class="col-md-10">
                    <select class="form-select" name="timezone" aria-label="Default select example">
                        @foreach(\DateTimeZone::listIdentifiers() as $tz)
                            <option value="{{ $tz }}" {{ config('settings.timezone') === $tz ? 'selected' : '' }}>
                                {{ $tz }}
                            </option>
                        @endforeach
                    </select>

                    @error('timezone')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="disable_register" class="col-md-2 col-form-label text-md-end">{{ __('Disable Register') }}</label>

                <div class="col-md-10">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="disable_register" value="{{ old('disable_register', config('settings.disable_register') == 1) ? '1' : '0' }}" id="disable_register" {{ config('settings.disable_register') == 1 ? 'checked' : '' }}>
                    </div>

                    @error('disable_register')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-3">
                <label for="register_confirm" class="col-md-2 col-form-label text-md-end">{{ __('Register Confirmation') }}</label>

                <div class="col-md-10">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="register_confirm" value="{{ old('register_confirm', config('settings.register_confirm') == 1) ? '1' : '0' }}" id="register_confirm" {{ config('settings.register_confirm') == 1 ? 'checked' : '' }}>
                    </div>

                    @error('register_confirm')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="row mb-0">
                <div class="col-md-10 offset-md-2">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Save') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('styles')

@endpush
@push('scripts')
    <script>
        const disable_register_checkbox = document.getElementById('disable_register');

        disable_register_checkbox.addEventListener('change', function () {
            disable_register_checkbox.value = this.checked ? '1' : '0';
        });
    </script>
    <script>
        const register_confirm_checkbox = document.getElementById('register_confirm');

        register_confirm_checkbox.addEventListener('change', function () {
            register_confirm_checkbox.value = this.checked ? '1' : '0';
        });
    </script>
@endpush
