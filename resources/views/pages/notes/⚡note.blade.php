<?php

use App\Models\Note;
use Livewire\Component;

new class extends Component
{
    public Note $note;

    public string $title = '';

    public bool $format = false;

    public bool $deleteCheck = false;

    public function mount()
    {
        if ((! auth()->check()) && $this->note->published_at === null) {
            return redirect('/');
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
    }


    public function createNote()
    {
        $note = Note::create([
            'title' => 'New note',
        ]);

        unset($this->notes);

        return $this->redirect(route('notes.note', ['note' => $note]), navigate: true);
    }

    public function deleteNote()
    {
        if (!$this->deleteCheck) {
            $this->deleteCheck = true;
            $this->dispatch('reset-delete-check');

            return;
        }

        $this->note->delete();
        $this->deleteCheck = false;
    }

    public function togglePublish()
    {
        $this->note->published_at === null
            ? $this->note->published_at = now()
            : $this->note->published_at = null;

        $this->note->save();
    }

    public function toggleDraft()
    {
        $this->note->draft = !$this->note->draft;

        $this->note->save();
    }

    public function togglePinned()
    {
        $this->note->pinned = !$this->note->pinned;

        $this->note->save();
    }

    public function toggleFormat()
    {
        $this->format = !$this->format;
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
    <header class="note__header">
        <h1 class="note__title {{ when(auth()->check(), 'sro') }}">{{ $title }}</h1>
        <a href="#nav" class="note__skip">Go to notes list</a>
        @auth()
            <textarea
                x-data="{
                    resize() {
                        const scrollTop = window.pageYOffset;
                        this.$el.style.height = 'auto';
                        this.$el.style.height = this.$el.scrollHeight + 'px';
                        window.scrollTo({ top: scrollTop });
                    },

                    onInput() {
                        this.resize();
                        $wire.$set('title', $el.value);
                    },
                }"
                x-init="resize()"
                x-resize.document="resize()"
                wire:ignore
                @input.debounce.500ms="onInput()"
                class="note__title note__title--edit"
            >{{ $title }}</textarea>

            <div class="note__buttons">
                <button class="note__button" wire:click="createNote">Create</button>
                <button class="note__button" wire:click="togglePublish">{{ $note->published_at === null ? 'Publish' : 'Unpublish' }}</button>
                <button class="note__button" wire:click="toggleDraft">{{ $note->draft ? 'Undraft' : 'Draft' }}</button>
                <button class="note__button" wire:click="togglePinned">{{ $note->pinned ? 'Unpin' : 'Pin' }}</button>
                <button class="note__button" wire:click="toggleFormat">{{ $format ? 'Show editor' : 'Show formatted' }}</button>
                <button
                    class="note__button note__button--danger"
                    wire:click="deleteNote"
                    @reset-delete-check.window="setTimeout(() => $wire.set('deleteCheck', false), 3000)"
                >
                    {{ $deleteCheck ? 'You sure?' : 'Delete' }}
                </button>
            </div>
        @endauth
    </header>

    <div class="note__content">
        @if(auth()->check() && !$format)
            <livewire:editor wire:model="content" :$note/>
        @else
            {!! str()->markdown($note->content) !!}
        @endif
    </div>

    <hr>

    <livewire:nav />
</div>
