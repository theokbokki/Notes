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
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Illuminate\Support\Str;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;

new class extends Component
{
    use WithFileUploads;

    public Note $note;

    public string $content = '';

    public $image;

    public function mount()
    {
        $this->content = $this->note->content;
    }

    public function updatedContent()
    {
        if ($this->content === $this->note->content) return;

        $this->note->update(['content' => $this->content ?? '']);
    }

    public function processImage()
    {
        $filename = Str::uuid().'.webp';
        File::ensureDirectoryExists(Storage::disk('public')->path('images'));
        $path = Storage::disk('public')->path('images/'.$filename);

        $manager = ImageManager::gd();
        $image = $manager->read($this->image->getRealPath());
        $image->scale(width: 1040)->encodeByExtension('webp', 85)->save($path);

        $this->dispatch('image-uploaded', url: Storage::url('images/'.$filename));
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
        },

        async onPaste(e) {
            const start = this.$el.selectionStart;
            const end = this.$el.selectionEnd;

            const contents = await navigator.clipboard.read();

            for (const item of contents) {
                if (!item.types.includes('image/png')) {
                    const blob = await item.getType('text/plain');
                    const text = await blob.text();

                    this.$el.value = this.$el.value.slice(0, start) + text + this.$el.value.slice(end);

                    const newPos = start + text.length;
                    this.$el.setSelectionRange(newPos, newPos);

                    this.onInput();
                    return;
                }

                const blob = await item.getType('image/png');
                const file = new File([blob], 'pasted.png', { type: 'image/png' });

                this.$wire.upload('image', file, () => {
                    this.$wire.call('processImage');
                });
            }
        },

        onImageUploaded(url) {
            const pos = this.$el.selectionStart;

            const markdown = `![image](${url})`;

            this.$el.value =
            this.$el.value.slice(0, pos) + markdown + this.$el.value.slice(pos);

            const newPos = pos + markdown.length;
            this.$el.setSelectionRange(newPos, newPos);

            this.onInput();
        }
    }"
    x-init="resize()"
    @input.debounce.500ms="onInput()"
    @paste.prevent="onPaste($event)"
    @image-uploaded="onImageUploaded($event.detail.url)"
    x-resize.document="resize()"
    class="editor"
    wire:ignore
>{{ $content }}</textarea>
