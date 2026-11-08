@extends('layouts.app')

@section('title', 'Registreren')

@section('content')
    <section class="form-card auth-card"><span class="eyebrow">SLUIT JE AAN</span><h1>Maak iets moois.</h1><p class="form-intro">Maak een account en deel je kennis met andere developers.</p>
        <form method="POST" action="{{ route('register.store') }}">@csrf
            <label for="name">Naam</label><input id="name" name="name" autocomplete="name" value="{{ old('name') }}" required>@error('name')<span class="field-error">{{ $message }}</span>@enderror
            <label for="username">Gebruikersnaam <span class="label-hint">je alias op het forum</span></label><input id="username" name="username" autocomplete="username" maxlength="40" value="{{ old('username') }}" required>@error('username')<span class="field-error">{{ $message }}</span>@enderror
            <label for="email">E-mailadres</label><input id="email" name="email" type="email" autocomplete="email" value="{{ old('email') }}" required>@error('email')<span class="field-error">{{ $message }}</span>@enderror
            <label for="password">Wachtwoord <span class="label-hint">minimaal 8 tekens</span></label><input id="password" name="password" type="password" autocomplete="new-password" required>@error('password')<span class="field-error">{{ $message }}</span>@enderror
            <label for="password_confirmation">Herhaal wachtwoord</label><input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>
            <button class="button button-full" type="submit">Account maken <span aria-hidden="true">→</span></button>
        </form>
        <p class="auth-switch">Heb je al een account? <a href="{{ route('login') }}">Log in</a>.</p>
    </section>
@endsection
