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
    if ($note = Note::latest()->first()) {
        return redirect()->route('notes.note', $note);
    }

    abort(404);
}
