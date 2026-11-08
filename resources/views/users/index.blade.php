@extends('layouts.app')

@section('title', 'Gebruikersbeheer')

@section('content')
    <div class="breadcrumbs"><a href="{{ route('home') }}">Forum</a><span>/</span><span>Gebruikers</span></div>
    <section class="section-heading admin-heading"><div><span class="eyebrow">BEHEER</span><h1>Gebruikers</h1><p>Bekijk en beheer de accounts van het forum.</p></div><span class="user-count">{{ $users->total() }} accounts</span></section>
    <div class="table-wrap"><table><thead><tr><th>Gebruiker</th><th>E-mailadres</th><th>Rol</th><th>Aangemeld</th><th></th></tr></thead><tbody>
        @foreach ($users as $user)
            <tr><td><a class="user-name" href="{{ route('users.edit', $user) }}"><span class="avatar avatar-small">{{ strtoupper(substr($user->username, 0, 1)) }}</span><span>{{ $user->username }}<small>{{ $user->name }}</small></span></a></td><td>{{ $user->email }}</td><td><span class="role-badge {{ $user->isAdmin() ? 'role-admin' : '' }}">{{ $user->isAdmin() ? 'Beheerder' : 'Gebruiker' }}</span></td><td>{{ $user->created_at->format('d-m-Y') }}</td><td class="table-actions"><a class="small-link" href="{{ route('users.edit', $user) }}">Bekijken / bewerken</a>
                @if ($user->id !== auth()->id())
                    <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('Deze gebruiker en diens berichten verwijderen?')">@csrf @method('DELETE')<button class="small-link danger-link" type="submit">Verwijderen</button></form>
                @endif
            </td></tr>
        @endforeach
    </tbody></table></div>
    <div class="pagination">{{ $users->links() }}</div>
@endsection
