<?php


use App\Models\Note;
use Livewire\Component;

new class extends Component
{
    public Note $note;

    public string $content = '';

    public function mount()
    {
        $this->content = $this->note->content;
    }

    public function autosave()
    {
        $this->note->update([
            'content' => $this->content,
        ]);
    }
};
?>

<div
    x-data="setupEditor($wire.entangle('{{ $attributes->wire('model')->value() }}'))"
    x-init="() => init($refs.editor)"
    wire:ignore
    {{ $attributes->whereDoesntStartWith('wire:model') }}
>
    <div x-ref="editor"></div>
</div>
