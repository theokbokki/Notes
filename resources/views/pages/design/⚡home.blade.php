<?php

use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

new #[Layout('layouts::design')] class extends Component
{
    use WithFileUploads;

    public array $images;

    public $image = null;

    public function mount()
    {
        $this->images = Arr::shuffle(Storage::disk('public')->files('design'));
    }

    public function updatedImage()
    {
        $filename = Str::uuid().'.webp';
        File::ensureDirectoryExists(Storage::disk('public')->path('design'));
        $path = Storage::disk('public')->path('design/'.$filename);

        $manager = ImageManager::gd();
        $image = $manager->read($this->image->getRealPath());
        $image->scale(width: 1080)->encodeByExtension('webp', 85)->save($path);

        $this->images = Arr::shuffle(Storage::disk('public')->files('design'));
    }
};
?>

<div class="design">
    <div class="design__sidebar">
        <div class="design__actions">
            <label class="design__toggle" for="theme">
                <span class="sro">Toggle theme</span>
                <input
                    type="checkbox"
                    name="theme"
                    id="theme"
                    class="sro design__theme"
                    x-init="$el.checked = window.matchMedia('(prefers-color-scheme: dark)').matches"
                />
            </label>

            <label class="design__add" for="add">
                <span class="sro">Toggle theme</span>
                <input type="file" name="add" id="add" class="sro" wire:model="image" />
            </label>
        </div>
        <div class="design__intro">
            <p class="design__text">Hey, I’m Théo and I like mixing graphic design with politics to give my opinions on the world and hopefully make it a better place</p>
            <p class="design__text">You can talk to me at <a href="mailto:hello@theoo.dev" class="design__link">hello@theoo.dev</a></p>
            <p class="design__text">I hope you have a wonderful day</p>
        </div>
    </div>
    <div class="design__images">
        @foreach($images as $image)
            <img src="{{ $image }}" alt="" class="design__image"/>
        @endforeach
    </div>
</div>

<script>
    Livewire.hook('morph.updated', () => {
        window.location.reload();
    });
</script>
