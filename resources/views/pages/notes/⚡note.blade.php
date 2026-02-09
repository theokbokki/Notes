<?php


use App\Models\Note;
use Livewire\Component;


new class extends Component
{
    public Note $note;

    public function render()
    {
        return $this->view()->layout('layouts::app', [
            'title' => $this->note->title,
        ]);
    }
};
?>

<div>
    @auth()
        <livewire:editor wire:model="content" wire:poll="autosave" :$note/>
    @else
        {!! $note->content !!}
    @endauth
</div>
