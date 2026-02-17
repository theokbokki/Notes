<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? config('app.name') }}</title>

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
