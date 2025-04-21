<meta property="og:url" content="{{ config('settings.site_url') }}" />
<meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}" />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="{{ config('settings.site_title') }}"/>
<meta property="og:title" content="{{ config('settings.site_title') }} - @yield('title')" />
<meta property="og:image" content="{{ asset(config('settings.site_logo')) }}" />
<meta property="og:image:secure_url" content="{{ asset(config('settings.site_logo')) }}" />
