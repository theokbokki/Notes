<?php

use App\Models\Note;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public Note $note;

    public bool $deleteCheck = false;

    public bool $format = false;

    #[Computed]
    public function notes()
    {
        return auth()->check()
            ? Note::latest('updated_at')->get()
            : Note::published()->latest('published_at')->get();
    }

    #[Computed]
    public function regularNotes()
    {
        return $this->notes
            ->where('pinned', false)
            ->where('draft', false);
    }

    #[Computed]
    public function pinnedNotes()
    {
        return $this->notes->where('pinned', true);
    }

    #[Computed]
    public function drafts()
    {
        return auth()->check()
            ? $this->notes->where('draft', true)
            : null;
    }

    public function createNote()
    {
        $note = Note::create([
            'title' => 'New note',
        ]);

        unset($this->notes);

        return $this->redirect(route('notes.note', ['note' => $note]), navigate: true);
    }

    public function togglePublish()
    {
        $this->note->published_at === null
            ? $this->note->published_at = now()
            : $this->note->published_at = null;

        $this->note->save();
    }

    public function toggleDraft()
    {
        $this->note->draft = !$this->note->draft;

        $this->note->save();
    }

    public function togglePinned()
    {
        $this->note->pinned = !$this->note->pinned;

        $this->note->save();
    }

    public function deleteNote()
    {
        if (!$this->deleteCheck) {
            $this->deleteCheck = true;
            $this->dispatch('reset-delete-check');

            return;
        }

        $this->note->delete();
        $this->deleteCheck = false;
    }

    public function toggleFormat()
    {
        $this->format = !$this->format;

        $this->dispatch('format-toggled', $this->format);
    }
};

?>

<nav class="nav">
    <div class="nav__notes" data-panel="notes">
        <div class="nav__header">
            <svg width="50" height="50" viewBox="-6.25 -6.25 62.5 62.5" version="1.1" xmlns="http://www.w3.org/2000/svg" style="transform:rotate(-90deg)">
                <circle r="15" cx="25" cy="25" fill="transparent" stroke="#ffffff80" stroke-width="4"></circle>
                <circle r="15" cx="25" cy="25" stroke="#ffffff" stroke-width="4" stroke-linecap="round" stroke-dashoffset="0" fill="transparent" stroke-dasharray="94.2px"></circle>
            </svg>
            <h2 class="nav__title">{{ $note->title }}</h2>
        </div>
        <div class="nav__main">
            @if(isset($this->drafts) && count($this->drafts))
                <section class="nav__section">
                    <h3 class="nav__subtitle">Drafts</h3>
                    <ul class="nav__list">
                        @foreach($this->drafts as $draft)
                            <li class="nav__item">
                                <a class="nav__link" href="{{ route('notes.note', ['note' => $draft]) }}">{{ $draft->title }}</a>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endif

            <section class="nav__section">
                <h3 class="nav__subtitle">Other Notes</h3>
                <ul class="nav__list">
                    @foreach($this->pinnedNotes as $pinned)
                        <li class="nav__item">
                            <a class="nav__link" href="{{ route('notes.note', ['note' => $pinned]) }}">{{ $pinned->title }}</a>
                        </li>
                    @endforeach
                    @foreach($this->regularNotes as $regular)
                        <li class="nav__item">
                            <a class="nav__link" href="{{ route('notes.note', ['note' => $regular]) }}">{{ $regular->title }}</a>
                        </li>
                    @endforeach
                </ul>
            </section>
        </div>
    </div>

    <div class="nav__more" data-panel="more">
        <div class="nav__icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
        </div>
        @auth()
        <div class="nav__buttons">
            <button class="nav__button" wire:click="createNote" title="Create">
                <span class="sro">Create</span>
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-plus-icon lucide-plus"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                </span>
            </button>
            <button class="nav__button" wire:click="togglePublish" title="{{ $note->published_at === null ? 'Publish' : 'Unpublish' }}">
                <span class="sro">{{ $note->published_at === null ? 'Publish' : 'Unpublish' }}</span>
                <span>
                    @if($note->published_at === null)
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-icon lucide-eye"><path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/></svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-off-icon lucide-eye-off"><path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49"/><path d="M14.084 14.158a3 3 0 0 1-4.242-4.242"/><path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143"/><path d="m2 2 20 20"/></svg>
                    @endif
                </span>
            </button>
            <button class="nav__button" wire:click="toggleDraft" title="{{ $note->draft ? 'Undraft' : 'Draft' }}">
                <span class="sro">{{ $note->draft ? 'Undraft' : 'Draft' }}</span>
                <span>
                    @if($note->draft)
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-dot-icon lucide-circle-dot"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="1"/></svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-dashed-icon lucide-circle-dashed"><path d="M10.1 2.182a10 10 0 0 1 3.8 0"/><path d="M13.9 21.818a10 10 0 0 1-3.8 0"/><path d="M17.609 3.721a10 10 0 0 1 2.69 2.7"/><path d="M2.182 13.9a10 10 0 0 1 0-3.8"/><path d="M20.279 17.609a10 10 0 0 1-2.7 2.69"/><path d="M21.818 10.1a10 10 0 0 1 0 3.8"/><path d="M3.721 6.391a10 10 0 0 1 2.7-2.69"/><path d="M6.391 20.279a10 10 0 0 1-2.69-2.7"/></svg>
                    @endif
                </span>
            </button>
            <button class="nav__button" wire:click="togglePinned" title="{{ $note->pinned ? 'Unpin' : 'Pin' }}">
                <span class="sro">{{ $note->pinned ? 'Unpin' : 'Pin' }}</span>
                <span>
                    @if($note->pinned)
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pin-off-icon lucide-pin-off"><path d="M12 17v5"/><path d="M15 9.34V7a1 1 0 0 1 1-1 2 2 0 0 0 0-4H7.89"/><path d="m2 2 20 20"/><path d="M9 9v1.76a2 2 0 0 1-1.11 1.79l-1.78.9A2 2 0 0 0 5 15.24V16a1 1 0 0 0 1 1h11"/></svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pin-icon lucide-pin"><path d="M12 17v5"/><path d="M9 10.76a2 2 0 0 1-1.11 1.79l-1.78.9A2 2 0 0 0 5 15.24V16a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-.76a2 2 0 0 0-1.11-1.79l-1.78-.9A2 2 0 0 1 15 10.76V7a1 1 0 0 1 1-1 2 2 0 0 0 0-4H8a2 2 0 0 0 0 4 1 1 0 0 1 1 1z"/></svg>
                    @endif
                </span>
            </button>
            <button class="nav__button" wire:click="toggleFormat" title="{{ $format ? 'Show editor' : 'Show formatted' }}">
                <span class="sro">{{ $format ? 'Show editor' : 'Show formatted' }}</span>
                <span>
                    @if($format)
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil-icon lucide-pencil"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/><path d="m15 5 4 4"/></svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil-off-icon lucide-pencil-off"><path d="m10 10-6.157 6.162a2 2 0 0 0-.5.833l-1.322 4.36a.5.5 0 0 0 .622.624l4.358-1.323a2 2 0 0 0 .83-.5L14 13.982"/><path d="m12.829 7.172 4.359-4.346a1 1 0 1 1 3.986 3.986l-4.353 4.353"/><path d="m15 5 4 4"/><path d="m2 2 20 20"/></svg>
                    @endif
                </span>
            </button>
            <button
                class="nav__button nav__button--danger"
                wire:click="deleteNote"
                @reset-delete-check.window="setTimeout(() => $wire.set('deleteCheck', false), 3000)"
                title="{{ $deleteCheck ? 'You sure?' : 'Delete' }}"
            >
                <span class="sro">{{ $deleteCheck ? 'You sure?' : 'Delete' }}</span>
                <span>
                    @if($deleteCheck)
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bomb-icon lucide-bomb"><circle cx="11" cy="13" r="9"/><path d="M14.35 4.65 16.3 2.7a2.41 2.41 0 0 1 3.4 0l1.6 1.6a2.4 2.4 0 0 1 0 3.4l-1.95 1.95"/><path d="m22 2-1.5 1.5"/></svg>
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash2-icon lucide-trash-2"><path d="M10 11v6"/><path d="M14 11v6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"/><path d="M3 6h18"/><path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                    @endif
                </span>
            </button>
        </div>
        @endauth
        <div class="nav__main">
            <section class="nav__section">
                <h3 class="nav__subtitle">Socials</h3>
                <ul class="nav__list">
                    <li class="nav__item">
                        <a class="nav__link" href="mailto:hello@theoo.dev">Email</a>
                    </li>
                    <li class="nav__item">
                        <a class="nav__link" href="https://instagram.com/theokbokki">Instagram</a>
                    </li>
                    <li class="nav__item">
                        <a class="nav__link" href="https://github.com/theokbokki">Github</a>
                    </li>
                </ul>
            </section>
            <section class="nav__section">
                <h3 class="nav__subtitle">Other</h3>
                <ul class="nav__list">
                    <li class="nav__item">
                        <a class="nav__link" href="{{ route('rss-feed') }}">RSS feed</a>
                    </li>
                    <li class="nav__item">
                        <a class="nav__link" href="https://feed.theoo.dev">Microblog</a>
                    </li>
                    <li class="nav__item">
                        <a class="nav__link" href="https://nuggets.theoo.dev">Nuggets</a>
                    </li>
                </ul>
            </section>
        </div>
    </div>
</nav>

<script>
    Livewire.hook('morph.updated', () => {
        nav.setAttribute('data-js', '');

        if (activePanel) {
            const openClass = activePanel === 'notes' ? 'nav__notes--open' : 'nav__more--open';
            const panel = activePanel === 'notes' ? notesPanel : morePanel;

            panel.classList.add(openClass);
        }
    });

    const nav = $wire.$el;
    nav.setAttribute('data-js', '');

    const progressCircle = nav.querySelector('.nav__header circle:last-child');
    const circumference = 94.2;

    function updateProgress() {
        const scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
        const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const progress = scrollHeight > 0 ? scrollTop / scrollHeight : 0;
        const offset = circumference * (1 - progress);

        progressCircle.style.strokeDashoffset = offset + 'px';
    }

    window.addEventListener('scroll', updateProgress, { passive: true });

    updateProgress();

    const notesPanel = nav.querySelector('[data-panel="notes"]');
    const morePanel = nav.querySelector('[data-panel="more"]');

    let activePanel = null;
    let currentFlip = null;

    notesPanel.addEventListener('click', (e) => {
        if (activePanel === 'notes') return;
        e.stopPropagation();

        openPanel('notes');
    });

    morePanel.addEventListener('click', (e) => {
        if (activePanel === 'more') return;
        e.stopPropagation();

        openPanel('more');
    });

    document.addEventListener('click', (e) => {
        if (activePanel && !nav.contains(e.target)) {
            closePanel();
        }
    });

    function openPanel(name) {
        const isNotes = name === 'notes';
        const panel = isNotes ? notesPanel : morePanel;
        const openClass = isNotes ? 'nav__notes--open' : 'nav__more--open';
        const items = panel.querySelectorAll('.nav__item, .nav__subtitle');

        if (activePanel && activePanel !== name) {
            const otherClass = (activePanel === 'notes') ? 'nav__notes--open' : 'nav__more--open';
            const otherPanel = (activePanel === 'notes') ? notesPanel : morePanel;

            otherPanel.classList.remove(otherClass);

            gsap.set(otherPanel, { clearProps: 'all' });
            gsap.set(otherPanel.querySelectorAll('.nav__item, .nav__subtitle'), {
                clearProps: 'opacity,y,filter',
            });
        }

        activePanel = name;

        const state = Flip.getState(panel, {
            props: 'padding',
        });

        panel.classList.add(openClass);

        if (currentFlip) currentFlip.kill();

        currentFlip = Flip.from(state, {
            duration: 0.5,
            ease: 'elastic.out(.2)',
            absolute: true,
        });

        gsap.set(items, { opacity: 0, y: 8, filter: 'blur(4px)' });
        gsap.to(items, {
            opacity: 1,
            y: 0,
            filter: 'blur(0px)',
            duration: 0.75,
            stagger: 0.025,
            ease: 'power3.out',
            delay: 0.1,
        });
    }

    function closePanel() {
        if (!activePanel) return;

        const isNotes = activePanel === 'notes';
        const panel = isNotes ? notesPanel : morePanel;
        const openClass = isNotes ? 'nav__notes--open' : 'nav__more--open';
        const items = panel.querySelectorAll('.nav__item, .nav__subtitle');

        gsap.to(items, {
            opacity: 0,
            y: 8,
            filter: 'blur(4px)',
            duration: 0.2,
            stagger: 0.015,
            ease: 'power3.in',
        });

        const state = Flip.getState(panel, {
            props: 'padding',
        });

        panel.classList.remove(openClass);

        if (currentFlip) currentFlip.kill();

        currentFlip = Flip.from(state, {
            duration: 0.25,
            ease: 'power4.out',
            absolute: true,
            onComplete: () => {
                gsap.set(panel, { clearProps: 'all' });
                gsap.set(items, { clearProps: 'opacity,y,filter' });
            },
        });

        activePanel = null;
    }
</script>
