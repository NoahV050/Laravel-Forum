@extends('layouts.app')

@section('title', 'Reactie bewerken')

@section('content')
    <div class="breadcrumbs"><a href="{{ route('home') }}">Forum</a><span>/</span><a href="{{ route('topics.show', [$thread, $topic]) }}">{{ $topic->title }}</a><span>/</span><span>Bewerken</span></div>
    <section class="form-card narrow-form"><span class="eyebrow">REACTIE AANPASSEN</span><h1>Reactie bewerken</h1>
        <form method="POST" action="{{ route('replies.update', [$thread, $topic, $reply]) }}">@csrf @method('PUT')
            <label for="body">Reactie</label><textarea id="body" name="body" rows="7" required>{{ old('body', $reply->body) }}</textarea>@error('body')<span class="field-error">{{ $message }}</span>@enderror
            <div class="form-actions"><a class="button button-quiet" href="{{ route('topics.show', [$thread, $topic]) }}">Annuleren</a><button class="button" type="submit">Wijzigingen opslaan</button></div>
        </form>
    </section>
@endsection
