<?php

use App\Models\Note;
use Illuminate\Support\Facades\Route;

Route::livewire('notes/{note}', 'pages::notes.note')
    ->name('notes.note')
    ->missing(fn () => fallback());

Route::livewire('/login', 'pages::auth.login')->name('login');

Route::fallback(fn () => fallback());

function fallback()
{
    if (auth()->check()) {
        return redirect()->route('notes.note', Note::latest()->first());
    }

    if ($note = Note::published()->latest()->first()) {
        return redirect()->route('notes.note', $note);
    }

    abort(404);
}
