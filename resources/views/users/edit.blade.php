@extends('layouts.app')

@section('title', 'Gebruiker bewerken')

@section('content')
    <div class="breadcrumbs"><a href="{{ route('home') }}">Forum</a><span>/</span><a href="{{ route('users.index') }}">Gebruikers</a><span>/</span><span>{{ $user->username }}</span></div>
    <section class="form-card narrow-form"><span class="eyebrow">GEBRUIKERSBEHEER</span><h1>Account van {{ $user->username }}</h1><p class="form-intro">Wachtwoorden worden nooit getoond. Laat het wachtwoordveld leeg om het huidige wachtwoord te behouden.</p>
        <div class="account-meta"><span>Aangemeld <strong>{{ $user->created_at->format('d-m-Y H:i') }}</strong></span><span>Laatst aangepast <strong>{{ $user->updated_at->format('d-m-Y H:i') }}</strong></span></div>
        <form method="POST" action="{{ route('users.update', $user) }}">@csrf @method('PUT')
            <label for="name">Naam</label><input id="name" name="name" value="{{ old('name', $user->name) }}" required>@error('name')<span class="field-error">{{ $message }}</span>@enderror
            <label for="username">Gebruikersnaam</label><input id="username" name="username" value="{{ old('username', $user->username) }}" maxlength="40" required>@error('username')<span class="field-error">{{ $message }}</span>@enderror
            <label for="email">E-mailadres</label><input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required>@error('email')<span class="field-error">{{ $message }}</span>@enderror
            <label for="role">Rol</label><select id="role" name="role"><option value="user" @selected(old('role', $user->role) === 'user')>Gebruiker</option><option value="admin" @selected(old('role', $user->role) === 'admin')>Beheerder</option></select>@error('role')<span class="field-error">{{ $message }}</span>@enderror
            <label for="password">Nieuw wachtwoord <span class="label-hint">optioneel, minimaal 8 tekens</span></label><input id="password" name="password" type="password" autocomplete="new-password">@error('password')<span class="field-error">{{ $message }}</span>@enderror
            <label for="password_confirmation">Herhaal nieuw wachtwoord</label><input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password">
            <div class="form-actions"><a class="button button-quiet" href="{{ route('users.index') }}">Terug</a><button class="button" type="submit">Wijzigingen opslaan</button></div>
        </form>
    </section>
@endsection
