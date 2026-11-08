@extends('layouts.app')

@section('title', 'Forum')

@section('content')
    <section class="hero">
        <div class="hero-copy">
            <span class="eyebrow"><span class="status-dot"></span> HET FORUM VOOR MAKERS</span>
            <h1>Goede code begint<br>met <span>goede vragen.</span></h1>
            <p>Deel waar je aan werkt, stel je vraag en help elkaar verder. Samen komen we er wel uit.</p>
            @guest
                <a class="button" href="{{ route('register') }}">Doe mee <span aria-hidden="true">→</span></a>
            @endguest
        </div>
        <div class="hero-art" aria-hidden="true"><div class="code-window"><div class="window-dots"><i></i><i></i><i></i></div><code><span>const</span> ideas = [<br>&nbsp;&nbsp;<em>"vragen"</em>,<br>&nbsp;&nbsp;<em>"antwoorden"</em>,<br>&nbsp;&nbsp;<em>"groeien"</em><br>];<br><b>ideas.forEach(share);</b></code></div><div class="hero-orbit orbit-one"></div><div class="hero-orbit orbit-two"></div></div>
        <div class="hero-stats"><strong>{{ $threads->count() }}</strong><span>forumonderdelen</span><span class="stats-divider"></span><span>PHP · Laravel · Vue · meer</span></div>
    </section>

    <section class="section-heading">
        <div><span class="eyebrow">ONTDEK HET FORUM</span><h2>Waar kunnen we je mee helpen?</h2></div>
        @auth
            @if (auth()->user()->isAdmin())
                <a class="text-link" href="{{ route('threads.create') }}">+ Onderdeel toevoegen</a>
            @endif
        @endauth
    </section>

    @if ($threads->isEmpty())
        <div class="empty-state"><span class="empty-icon">✳</span><h3>Het forum begint hier</h3><p>Er zijn nog geen forumonderdelen. De beheerder kan het eerste onderdeel toevoegen.</p></div>
    @else
        <div class="thread-grid">
            @foreach ($threads as $thread)
                <a class="thread-card" href="{{ route('threads.show', $thread) }}">
                    <div class="card-top"><span class="category-icon">{{ strtoupper(substr($thread->title, 0, 1)) }}</span><span class="card-arrow" aria-hidden="true">↗</span></div>
                    <h3>{{ $thread->title }}</h3>
                    <p class="thread-description">{{ $thread->description }}</p>
                    <div class="card-meta"><span>door {{ $thread->user->username }}</span><span>{{ $thread->topics_count }} {{ $thread->topics_count === 1 ? 'bericht' : 'berichten' }}</span></div>
                </a>
            @endforeach
        </div>
    @endif
@endsection
