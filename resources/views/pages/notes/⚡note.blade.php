<?php

use App\Models\Note;
use Livewire\Component;

new class extends Component
{
    public Note $note;
};
?>

<div>
    <h1>{{ $note->title }}</h1>
    {!! $note->content !!}
</div>
