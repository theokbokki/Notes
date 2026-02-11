<?php

use App\Models\Note;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    #[Computed]
    public function notes()
    {
        return auth()->check() ? Note::all() : Note::published()->get();
    }

    public function createNote()
    {
        $note = Note::create([
            'title' => 'New note',
        ]);

        unset($this->notes);

        return $this->redirect(route('notes.note', ['note' => $note]), navigate: true);
    }

    #[On('editor-content-updated')]
    public function handleNoteUpdate()
    {
        unset($this->notes);
    }
};
?>

<nav class="nav">
    <h2 class="sro">Main nav</h2>
    @auth()
        <button class="nav__create" wire:click="createNote">Create Note</button>
    @endauth
    <ul class="nav__list">
        @foreach($this->notes as $note)
            <li class="nav__item">
                <a class="nav__link" href="{{route('notes.note', ['note' => $note]) }}">{{ $note->title }}</a>
                <p class="nav__description">{{ str()->limit(strip_tags($note->content), 150) }}</p>
            </li>
        @endforeach
    </ul>
</nav>
