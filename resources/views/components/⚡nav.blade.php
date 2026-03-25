<?php

use App\Models\Note;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public Note $note;

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
    const nav = $wire.$el;
    nav.setAttribute('data-js', '');

    const notesPanel = nav.querySelector('[data-panel="notes"]');
    const morePanel = nav.querySelector('[data-panel="more"]');


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

        // Close other panel instantly if open
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
            props: 'padding,borderRadius',
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
            props: 'padding,borderRadius',
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
