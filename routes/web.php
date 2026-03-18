<?php

use App\Models\Note;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RssFeedController;

Route::livewire('notes/{note}', 'pages::notes.note')
    ->name('notes.note')
    ->missing(fn () => fallback());

Route::livewire('/login', 'pages::auth.login')->name('login');

Route::get('feed', RssFeedController::class)->name('rss-feed');

Route::fallback(fn () => fallback());

function fallback()
{
    if (auth()->check()) {
        return redirect()->route('notes.note', Note::latest('updated_at')->first());
    }

    if ($note = Note::published()->latest('published_at')->first()) {
        return redirect()->route('notes.note', $note);
    }

    abort(404);
}
