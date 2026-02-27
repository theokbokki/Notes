<?php

namespace App\Http\Controllers;

use App\Models\Note;

class RssFeedController extends Controller
{
    public function __invoke()
    {
        $notes = Note::latest()->published()->get();

        return response()->view('rss', [
            'notes' => $notes
        ])->header('Content-Type', 'text/xml');
    }
}
