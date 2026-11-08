@extends('layouts.app')

@section('title', 'Bericht bewerken')

@section('content')
    <div class="breadcrumbs"><a href="{{ route('home') }}">Forum</a><span>/</span><a href="{{ route('threads.show', $thread) }}">{{ $thread->title }}</a><span>/</span><span>Bewerken</span></div>
    <section class="form-card narrow-form"><span class="eyebrow">BERICHT AANPASSEN</span><h1>Bericht bewerken</h1>
        <form method="POST" action="{{ route('topics.update', [$thread, $topic]) }}">@csrf @method('PUT')
            <label for="title">Titel</label><input id="title" name="title" maxlength="160" value="{{ old('title', $topic->title) }}" required>@error('title')<span class="field-error">{{ $message }}</span>@enderror
            <label for="body">Bericht</label><textarea id="body" name="body" rows="8" required>{{ old('body', $topic->body) }}</textarea>@error('body')<span class="field-error">{{ $message }}</span>@enderror
            <div class="form-actions"><a class="button button-quiet" href="{{ route('topics.show', [$thread, $topic]) }}">Annuleren</a><button class="button" type="submit">Wijzigingen opslaan</button></div>
        </form>
        @if (auth()->user()->isAdmin())
            <form class="delete-form" method="POST" action="{{ route('topics.destroy', [$thread, $topic]) }}" onsubmit="return confirm('Dit bericht en alle reacties verwijderen?')">@csrf @method('DELETE')<button class="small-link danger-link" type="submit">Bericht definitief verwijderen</button></form>
        @endif
    </section>
@endsection
