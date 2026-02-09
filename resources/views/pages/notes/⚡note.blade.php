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

<div>
    <h1 class="{{ when(auth()->check(), 'sro') }}">{{ $title }}</h1>
    @auth()
        <input type="text" wire:model.live.debounce.500ms="title" />
    @endauth

    <livewire:nav />

    @auth()
        <livewire:editor wire:model="content" :$note/>
    @else
        {!! $note->content !!}
    @endauth
</div>
