<meta property="og:url" content="{{ config('settings.site_url', 'http://localhost') }}" />
<meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}" />
<meta property="og:type" content="website" />
<meta property="og:site_name" content="{{ config('settings.site_title', 'iSRO CMS v2') }}"/>
<meta property="og:title" content="{{ config('settings.site_title', 'iSRO CMS v2') }} - @yield('title')" />
<meta property="og:image" content="{{ asset(config('settings.site_logo', 'images/logo.png')) }}" />
<meta property="og:image:secure_url" content="{{ asset(config('settings.site_logo', 'images/logo.png')) }}" />
