<?php

use App\Models\Note;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    #[Computed]
    public function notes()
    {
        return auth()->check() ? Note::all() : Note::published()->get();
    }

    public function createNote()
    {
        $note = Note::create([
            'title' => 'New note',
        ]);

        unset($this->notes);

        return $this->redirect(route('notes.note', ['note' => $note]), navigate: true);
    }
};
?>

<nav>
    @auth()
        <div>
            <button wire:click="createNote">Create Note</button>
        </div>
    @endauth
    <div>
        @foreach($this->notes as $note)
            <div>
                <a href="{{route('notes.note', ['note' => $note]) }}">{{ $note->title }}</a>
                <p>{{ str()->limit(strip_tags($note->content), 150) }}
            </div>
        @endforeach
    </div>
</nav>
