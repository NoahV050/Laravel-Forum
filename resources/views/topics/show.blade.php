@extends('layouts.app')

@section('title', $topic->title)

@section('content')
    <div class="breadcrumbs"><a href="{{ route('home') }}">Forum</a><span>/</span><a href="{{ route('threads.show', $thread) }}">{{ $thread->title }}</a><span>/</span><span>Bericht</span></div>
    <article class="post-card">
        <div class="post-topline"><span class="eyebrow">{{ $thread->title }}</span>
            @auth
                @if (auth()->user()->isAdmin() || auth()->id() === $topic->user_id)
                    <a class="text-link" href="{{ route('topics.edit', [$thread, $topic]) }}">Bericht bewerken</a>
                @endif
            @endauth
        </div>
        <h1>{{ $topic->title }}</h1>
        <div class="byline">Door <strong>{{ $topic->user->username }}</strong> · {{ $topic->created_at->format('d-m-Y H:i') }}@if ($topic->updated_at->gt($topic->created_at)) · bewerkt {{ $topic->updated_at->format('d-m-Y H:i') }}@endif</div>
        <div class="post-body">{{ $topic->body }}</div>
    </article>

    <section class="section-heading"><div><span class="eyebrow">{{ $topic->replies->count() }} REACTIES</span><h2>De conversatie</h2></div></section>
    <div class="reply-list">
        @forelse ($topic->replies as $reply)
            <article class="reply-card"><div class="avatar avatar-small">{{ strtoupper(substr($reply->user->username, 0, 1)) }}</div><div class="reply-content"><div class="reply-byline"><strong>{{ $reply->user->username }}</strong><span>{{ $reply->created_at->format('d-m-Y H:i') }}@if ($reply->updated_at->gt($reply->created_at)) · bewerkt @endif</span></div><p>{{ $reply->body }}</p>
                @auth
                    @if (auth()->user()->isAdmin() || auth()->id() === $reply->user_id)
                        <div class="inline-actions"><a class="small-link" href="{{ route('replies.edit', [$thread, $topic, $reply]) }}">Bewerken</a>
                            @if (auth()->user()->isAdmin())
                                <form method="POST" action="{{ route('replies.destroy', [$thread, $topic, $reply]) }}" onsubmit="return confirm('Deze reactie verwijderen?')">@csrf @method('DELETE')<button class="small-link danger-link" type="submit">Verwijderen</button></form>
                            @endif
                        </div>
                    @endif
                @endauth
            </div></article>
        @empty
            <p class="muted">Er zijn nog geen reacties. Deel als eerste je antwoord.</p>
        @endforelse
    </div>

    @auth
        <section class="form-card composer"><span class="eyebrow">DOE MEE</span><h2>Plaats een reactie</h2>
            <form method="POST" action="{{ route('replies.store', [$thread, $topic]) }}">@csrf
                <label for="body">Je reactie</label><textarea id="body" name="body" rows="4" required>{{ old('body') }}</textarea>@error('body')<span class="field-error">{{ $message }}</span>@enderror
                <button class="button" type="submit">Reactie plaatsen <span aria-hidden="true">→</span></button>
            </form>
        </section>
    @else
        <div class="login-prompt">Je moet ingelogd zijn om te reageren. <a href="{{ route('login') }}">Log in</a> of <a href="{{ route('register') }}">maak een account</a>.</div>
    @endauth
@endsection
