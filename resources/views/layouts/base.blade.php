<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ isset($shtmlClass) ? $shtmlClass : '' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @hasSection ('parentTitle')
            <title>@yield('parentTitle') - {{ config('app.name') }}</title>
        @else
            <title>{{ config('app.name') }}</title>
        @endif
        <!-- Favicon -->
		{{-- <link rel="shortcut icon" href="{{ asset('assets/icon.ico') }}"> --}}

        <!-- Fonts -->
        @yield('baseFonts')

        <!-- Style -->
        <link href="{{ mix('assets/css/app.css') }}" rel="stylesheet">
        <!-- CSS Plugins -->
        @yield('baseCSSPlugins')
        <!-- CSS Inline -->
        @yield('baseCSSInline')

        <!-- Manifest -->

        <!-- Livewire -->
        @livewireStyles
        @livewireScripts
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        @stack('css')
        @stack('javascript')

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>

    <body class="{{ isset($sbodyClass) ? $sbodyClass : '' }}" x-data>
        @yield('body')

        <!-- Script -->
        <script src="{{ mix('assets/js/app.js') }}"></script>
        <script src="{{ mix('assets/plugins/moment/moment.min.js') }}"></script>
        <script src="{{ mix('assets/plugins/moment/moment-timezone-with-data.min.js') }}"></script>
        <!-- JS Plugins -->
        @yield('baseJsPlugins')
        <!-- Script Inline -->
        @yield('baseJsInline')
    </body>
</html>
