@extends('layouts.app')

@section('title', $thread->title)

@section('content')
    <div class="breadcrumbs"><a href="{{ route('home') }}">Forum</a><span>/</span><span>{{ $thread->title }}</span></div>
    <section class="thread-header">
        <span class="eyebrow">FORUMONDERDEEL</span>
        <div class="heading-row"><h1>{{ $thread->title }}</h1>
            @auth
                @if (auth()->user()->isAdmin())
                    <div class="inline-actions"><a class="button button-quiet" href="{{ route('threads.edit', $thread) }}">Bewerken</a>
                        <form method="POST" action="{{ route('threads.destroy', $thread) }}" onsubmit="return confirm('Dit onderdeel en alle berichten verwijderen?')">@csrf @method('DELETE')<button class="button button-danger" type="submit">Verwijderen</button></form>
                    </div>
                @endif
            @endauth
        </div>
        <p>{{ $thread->description }}</p>
        <div class="byline">Aangemaakt door <strong>{{ $thread->user->username }}</strong> · {{ $thread->created_at->format('d-m-Y') }}</div>
    </section>

    <section class="section-heading"><div><span class="eyebrow">{{ $thread->topics_count }} BERICHTEN</span><h2>Gesprekken</h2></div></section>
    @forelse ($thread->topics as $topic)
        <article class="topic-row">
            <div class="avatar">{{ strtoupper(substr($topic->user->username, 0, 1)) }}</div>
            <div class="topic-summary"><a href="{{ route('topics.show', [$thread, $topic]) }}"><h3>{{ $topic->title }}</h3></a><p>{{ \Illuminate\Support\Str::limit($topic->body, 170) }}</p><span>Gestart door {{ $topic->user->username }} · {{ $topic->created_at->format('d-m-Y H:i') }}</span></div>
            <a class="reply-count" href="{{ route('topics.show', [$thread, $topic]) }}">{{ $topic->replies_count }} <span>reacties</span> <b>→</b></a>
        </article>
    @empty
        <div class="empty-state compact"><h3>Nog geen gesprekken</h3><p>Wees de eerste die een vraag stelt in dit onderdeel.</p></div>
    @endforelse

    @auth
        <section class="form-card composer"><span class="eyebrow">START EEN GESPREK</span><h2>Nieuw bericht</h2>
            <form method="POST" action="{{ route('topics.store', $thread) }}">@csrf
                <label for="title">Titel</label><input id="title" name="title" value="{{ old('title') }}" maxlength="160" required>@error('title')<span class="field-error">{{ $message }}</span>@enderror
                <label for="body">Je bericht</label><textarea id="body" name="body" rows="5" required>{{ old('body') }}</textarea>@error('body')<span class="field-error">{{ $message }}</span>@enderror
                <button class="button" type="submit">Bericht plaatsen <span aria-hidden="true">→</span></button>
            </form>
        </section>
    @else
        <div class="login-prompt">Wil je een vraag stellen? <a href="{{ route('login') }}">Log in</a> of <a href="{{ route('register') }}">maak een account</a>.</div>
    @endauth
@endsection
