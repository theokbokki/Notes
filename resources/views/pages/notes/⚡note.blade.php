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
            'notes' => auth()->check() ? Note::all() : Note::published()->get(),
        ]);
    }
};
?>

<div>
    {!! $note->content !!}
</div>
