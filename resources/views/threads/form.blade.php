@extends('layouts.app')

@section('title', $heading)

@section('content')
    <div class="breadcrumbs"><a href="{{ route('home') }}">Forum</a><span>/</span><span>{{ $heading }}</span></div>
    <section class="form-card narrow-form"><span class="eyebrow">BEHEER</span><h1>{{ $heading }}</h1>
        <form method="POST" action="{{ $thread->exists ? route('threads.update', $thread) : route('threads.store') }}">@csrf @if ($thread->exists) @method('PUT') @endif
            <label for="title">Naam van het onderdeel</label><input id="title" name="title" maxlength="120" value="{{ old('title', $thread->title) }}" required>@error('title')<span class="field-error">{{ $message }}</span>@enderror
            <label for="description">Korte beschrijving</label><textarea id="description" name="description" rows="4" maxlength="1000" required>{{ old('description', $thread->description) }}</textarea>@error('description')<span class="field-error">{{ $message }}</span>@enderror
            <div class="form-actions"><a class="button button-quiet" href="{{ $thread->exists ? route('threads.show', $thread) : route('home') }}">Annuleren</a><button class="button" type="submit">Opslaan</button></div>
        </form>
    </section>
@endsection
