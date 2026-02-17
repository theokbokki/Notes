<?php

use App\Models\Note;
use Livewire\Component;

new class extends Component
{
    public Note $note;

    public string $title = '';

    public bool $format = false;

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

    public function togglePublish()
    {
        $this->note->published_at === null
            ? $this->note->published_at = now()
            : $this->note->published_at = null;

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
    <h1 class="note__title {{ when(auth()->check(), 'sro') }}">{{ $title }}</h1>
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
            <button class="note__button" wire:click="createNote">Create Note</button>
            <button class="note__button" wire:click="togglePublish">{{ $note->published_at === null ? 'Publish' : 'Unpublish' }}</button>
            <button class="note__button" wire:click="toggleFormat">{{ $format ? 'Show editor' : 'Show formatted' }}</button>
        </div>
    @endauth

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
