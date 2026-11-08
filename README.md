# DevPraat Forum

Een Nederlandstalig forum voor studenten en developers, gebouwd met Laravel 12 en PHP 8.2+. Forumonderdelen bevatten berichten; berichten bevatten reacties. Bezoekers kunnen alles lezen, geregistreerde gebruikers kunnen berichten en reacties plaatsen en hun eigen bijdragen aanpassen. Beheerders beheren forumonderdelen en gebruikers en kunnen bijdragen verwijderen.

## Installeren

```sh
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Open daarna <http://127.0.0.1:8000>. Laravel gebruikt standaard SQLite; maak zo nodig eerst `database/database.sqlite` aan. Stel voor een eigen installatie `DB_*` waarden in `.env` in.

## Beheerdersaccount

De seeder maakt een eerste beheerder aan:

- E-mail: `admin@devpraat.local`
- Wachtwoord: `ChangeMe123!`

Stel vóór het seeden voor een eigen installatie `FORUM_ADMIN_EMAIL` en `FORUM_ADMIN_PASSWORD` in je lokale `.env` in. Wijzig het initiële wachtwoord na de eerste keer inloggen. Publieke registratie geeft altijd de rol `user`; alleen een beheerder kan rollen aanpassen.

## Belangrijkste routes

- `/` of `/threads` — openbaar overzicht van forumonderdelen
- `/thread/{id}` — forumonderdeel en gesprekken
- `/thread/{thread}/topic/{topic}` — volledig bericht en reacties
- `/register` en `/login` — account aanmaken en inloggen
- `/users` — gebruikersbeheer (alleen beheerder)

Gebruikers kunnen hun eigen berichten en reacties aanpassen, maar niet verwijderen. Alleen beheerders kunnen verwijderen. Gebruikerslijsten tonen nooit wachtwoorden.

## Tests

```sh
php artisan test
```
