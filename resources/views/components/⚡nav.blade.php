<?php

use App\Models\Note;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    #[Computed]
    public function notes()
    {
        return auth()->check()
            ? Note::latest()->get()
            : Note::published()->latest()->get();
    }

    #[Computed]
    public function pinnedNotes()
    {
        return $this->notes->where('pinned', true);
    }

    #[Computed]
    public function drafts()
    {
        return auth()->check()
            ? $this->notes->where('draft', true)
            : null;
    }
};
?>

<div id="nav">
    <nav class="nav">
        <h2>Pinned notes</h2>

        <ul class="nav__list">
            @foreach($this->pinnedNotes as $note)
                <li class="nav__item">
                    <a class="nav__link" href="{{route('notes.note', ['note' => $note]) }}">{{ $note->title }}</a>
                </li>
            @endforeach
        </ul>
    </nav>

    @if(isset($this->drafts) && count($this->drafts))
    <nav class="nav">
        <h2>Drafts</h2>

        <ul class="nav__list">
            @foreach($this->drafts as $note)
                <li class="nav__item">
                    <a class="nav__link" href="{{route('notes.note', ['note' => $note]) }}">{{ $note->title }}</a>
                </li>
            @endforeach
        </ul>
    </nav>
    @endisset

    <nav class="nav">
        <h2>All my notes</h2>

        <ul class="nav__list">
            @foreach($this->notes as $note)
                <li class="nav__item">
                    <a class="nav__link" href="{{route('notes.note', ['note' => $note]) }}">{{ $note->title }}</a>
                </li>
            @endforeach
        </ul>
    </nav>
</div>
