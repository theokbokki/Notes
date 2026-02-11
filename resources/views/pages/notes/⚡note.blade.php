<?php

use App\Models\Note;
use Livewire\Component;

new class extends Component
{
    public Note $note;

    public string $title = '';

    public function mount()
    {
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
    @endauth

    <livewire:nav />

    <div class="note__content">
        @auth()
            <livewire:editor wire:model="content" :$note/>
        @else
            {!! $note->content !!}
        @endauth
    </div>
</div>
