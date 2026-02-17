<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? config('app.name') }}</title>

        <link rel="apple-touch-icon" sizes="57x57" href="{{ Vite::asset('resources/favicons/apple-icon-57x57.png') }}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ Vite::asset('resources/favicons/apple-icon-60x60.png') }}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ Vite::asset('resources/favicons/apple-icon-72x72.png') }}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ Vite::asset('resources/favicons/apple-icon-76x76.png') }}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ Vite::asset('resources/favicons/apple-icon-114x114.png') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ Vite::asset('resources/favicons/apple-icon-120x120.png') }}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ Vite::asset('resources/favicons/apple-icon-144x144.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ Vite::asset('resources/favicons/apple-icon-152x152.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ Vite::asset('resources/favicons/apple-icon-180x180.png') }}">

        <link rel="icon" type="image/png" sizes="192x192" href="{{ Vite::asset('resources/favicons/android-icon-192x192.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ Vite::asset('resources/favicons/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ Vite::asset('resources/favicons/favicon-96x96.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ Vite::asset('resources/favicons/favicon-16x16.png') }}">

        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ Vite::asset('resources/favicons/ms-icon-144x144.png') }}">
        <meta name="theme-color" content="#ffffff">


        @vite(['resources/css/app.scss', 'resources/js/app.js'])

        @livewireStyles
    </head>
    <body style="--offset: {{ now('Europe/Brussels')->diffInSeconds(now('Europe/Brussels')->startOfDay()) }}s">
        {{ $slot }}

        <div class="backdrop">
            <div class="backdrop__window backdrop__window--left"></div>
            <div class="backdrop__window backdrop__window--right"></div>
            <div class="backdrop__leaves"></div>
        <div>

        @livewireScriptConfig
    </body>
</html>
