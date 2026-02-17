<?php

use App\Models\Note;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    #[Computed]
    public function notes()
    {
        return auth()->check() ? Note::all() : Note::published()->get();
    }
};
?>

<nav class="nav">
    <h2>All my notes</h2>

    <ul class="nav__list">
        @foreach($this->notes as $note)
            <li class="nav__item">
                <a class="nav__link" href="{{route('notes.note', ['note' => $note]) }}">{{ $note->title }}</a>
            </li>
        @endforeach
    </ul>
</nav>
