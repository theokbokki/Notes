<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? config('app.name') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
    </head>
    <body>
        <h1>{{ $title }}</h1>

        <nav>
           @foreach($notes as $note)
                <div>
                    <a href="{{route('notes.note', ['note' => $note]) }}">{{ $note->title }}</a>
                    <p>{{ str()->limit(strip_tags($note->content), 150) }}
                </div>
           @endforeach
        </nav>

        {{ $slot }}

        @livewireScripts
    </body>
</html>
