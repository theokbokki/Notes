<?php

use App\Models\Note;
use Livewire\Component;
use Livewire\Attributes\On;

new class extends Component
{
    public Note $note;

    public string $title = '';

    public bool $format = true;

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

    #[On('format-toggled')]
    public function toggleFormat(bool $format)
    {
        $this->format = $format;
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
    <div class="note">
        <header class="note__header">
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
            @endauth
        </header>

        <div class="note__content">
            @if(auth()->check() && !$format)
                <livewire:editor wire:model="content" :$note/>
            @else
                {!! str()->markdown($note->content) !!}
            @endif
        </div>
    </div>
    <livewire:nav :$note />
</div>
