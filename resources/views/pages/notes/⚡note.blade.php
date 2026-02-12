<?php

use App\Models\Note;
use Livewire\Component;

new class extends Component
{
    public Note $note;

    public string $title = '';

    public function mount()
    {
        if ((! auth()->check()) && $this->note->published_at === null) {
            abort(404);
        }

        $this->title = $this->note->title;
    }

    public function updatedTitle()
    {
        if ($this->title === $this->note->title) {
            return;
        }

        $this->note->update([
            'title' => $this->title ?? '',
        ]);

        return $this->redirect(route('notes.note', ['note' => $this->note]), navigate: true);
    }


    public function createNote()
    {
        $note = Note::create([
            'title' => 'New note',
        ]);

        unset($this->notes);

        return $this->redirect(route('notes.note', ['note' => $note]), navigate: true);
    }

    public function togglePublish()
    {
        $this->note->published_at === null
            ? $this->note->published_at = now()
            : $this->note->published_at = null;

        $this->note->save();
    }

    public function render()
    {
        return $this->view()->layout('layouts::app', [
            'title' => $this->title,
        ]);
    }
};
?>

<div class="note">
    <h1 class="note__title {{ when(auth()->check(), 'sro') }}">{{ $title }}</h1>
    @auth()
        <input type="text" wire:model.live.debounce.500ms="title" class="note__title note__title--edit" />
        <button class="nav__create" wire:click="createNote">Create Note</button>
        <button class="nav__create" wire:click="togglePublish">{{ $note->published_at === null ? 'Publish' : 'Unpublish' }}</button>
    @endauth

    <div class="note__content">
        @auth()
            <livewire:editor wire:model="content" :$note/>
        @else
            {!! str()->markdown($note->content) !!}
        @endauth
    </div>

    <hr>

    <livewire:nav />
</div>
