<?php

namespace Database\Seeders;

use App\Models\Thread;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(['email' => env('FORUM_ADMIN_EMAIL', 'admin@devpraat.local')], [
            'name' => 'Forumbeheerder',
            'username' => 'beheerder',
            'password' => Hash::make(env('FORUM_ADMIN_PASSWORD', 'ChangeMe123!')),
            'role' => 'admin',
        ]);

        foreach ([
            ['Laravel & PHP', 'Vragen en tips over PHP, Laravel, databases en backendontwikkeling.'],
            ['JavaScript & Vue', 'Praat mee over JavaScript, Vue, frontend en alles wat in de browser gebeurt.'],
            ['Tools & Werkwijze', 'Deel je favoriete tools, workflow, projecten en handige ontwikkeltips.'],
        ] as [$title, $description]) {
            Thread::firstOrCreate(['title' => $title], [
                'description' => $description,
                'user_id' => $admin->id,
            ]);
        }
    }
}
