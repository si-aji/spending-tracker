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

        @stack('javascript')

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>

    <body class="@yield('sbodyClass')">
        @yield('body')

        <!-- Script -->
        <script src="{{ mix('assets/js/app.js') }}"></script>
        <!-- JS Plugins -->
        @yield('baseJsPlugins')
        <!-- Script Inline -->
        @yield('baseJsInline')
    </body>
</html>
