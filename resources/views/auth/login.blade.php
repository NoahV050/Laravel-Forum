@extends('layouts.app')

@section('title', 'Inloggen')

@section('content')
    <section class="form-card auth-card"><span class="eyebrow">FIJN DAT JE ER BENT</span><h1>Welkom terug.</h1><p class="form-intro">Log in en praat mee met de community.</p>
        <form method="POST" action="{{ route('login.store') }}">@csrf
            <label for="email">E-mailadres</label><input id="email" name="email" type="email" autocomplete="email" value="{{ old('email') }}" required autofocus>@error('email')<span class="field-error">{{ $message }}</span>@enderror
            <label for="password">Wachtwoord</label><input id="password" name="password" type="password" autocomplete="current-password" required>@error('password')<span class="field-error">{{ $message }}</span>@enderror
            <label class="checkbox-label"><input type="checkbox" name="remember"> Ingelogd blijven</label>
            <button class="button button-full" type="submit">Inloggen <span aria-hidden="true">→</span></button>
        </form>
        <p class="auth-switch">Nog geen account? <a href="{{ route('register') }}">Registreer je hier</a>.</p>
    </section>
@endsection
