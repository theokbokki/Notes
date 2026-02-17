<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (app()->env() === 'production') {
            User::factory()->create([
                'name' => 'Théo',
                'email' => 'hello@theoo.dev',
                'password' => env('MASTER_PASSWORD'),
            ]);
        } else{
            User::factory()->create([
                'name' => 'Théo',
                'email' => 'hello@theoo.dev',
                'password' => bcrypt('change_this'),
            ]);

            $this->call([
                NoteSeeder::class,
            ]);
        }
    }
}
