<?php

use App\Models\Note;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

new class extends Component
{
    use WithFileUploads;

    public Note $note;

    public string $content = '';

    #[Validate('image|max:5120')]
    public $image;

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

    public function processImage($pos = null)
    {
        $filename = Str::uuid().'.webp';
        File::ensureDirectoryExists(Storage::disk('public')->path('images'));
        $path = Storage::disk('public')->path('images/'.$filename);

        $manager = ImageManager::gd();
        $image = $manager->read($this->image->getRealPath());
        $image->scale(width: 1040)->encodeByExtension('webp', 85)->save($path);

        $this->dispatch(
          'editor-image-uploaded',
          url: Storage::url('images/'.$filename),
          pos: $pos
        );
    }
};
?>

<div
    x-data="setupEditor($wire.entangle('{{ $attributes->wire('model')->value() }}'))"
    x-init="() => initialize($refs.editor)"
    wire:ignore
    {{ $attributes->whereDoesntStartWith('wire:model') }}
>
    <div x-ref="editor"></div>
</div>
