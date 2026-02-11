{{--
use App\Models\Note;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Livewire\Attributes\Validate;

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

    public function saveContent(string $content)
    {
        if ($content === $this->note->content) {
            return;
        }

        $this->content = $content;

        $this->note->update([
            'content' => $content,
        ]);

        $this->dispatch('editor-content-updated');
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

<div
    x-data="setupEditor(@js($content), $refs.editor)"
    wire:ignore
>
    <div x-ref="editor"></div>
</div> --}}


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

    public function updatedContent()
    {
        if ($this->content === $this->note->content) return;

        $this->note->update(['content' => $this->content ?? '']);
    }
}

?>

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
            $wire.$set('content', $el.value);
        }
    }"
    x-init="resize()"
    @input.debounce.500ms="onInput()"
    x-resize.document="resize()"
    class="editor"
    wire:ignore
>{{ $content }}</textarea>
