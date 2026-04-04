<?php

use Livewire\Component;
use Livewire\Attributes\Layout;

new #[Layout('layouts::design')] class extends Component
{
    public array $images;

    public function mount()
    {
        $this->images = Arr::shuffle(Storage::disk('public')->files('design'));
    }
};
?>

<div class="design">
    <div class="design__intro">
        <p class="design__text">Hey, I’m Théo and I like mixing graphic design with politics to give my opinions on the world and hopefully make it a better place.</p>
        <p class="design__text">You can talk to me at <a href="mailto:hello@theoo.dev" class="design__link">hello@theoo.dev</a></p>
        <p class="design__text">I hope you have a wonderful day</p>
    </div>
    <div class="design__images">
        @foreach($images as $image)
            <img src="{{ $image }}" alt="" class="design__image"/>
        @endforeach
    </di>
</div>
