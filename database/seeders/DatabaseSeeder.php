<?php

namespace Database\Seeders;

use App\Models\Source;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Source::factory()->create([
            'name' => 'Symfony Blog',
            'host' => 'https://symfony.com',
            'url_to_parse' => 'https://symfony.com/blog/category/a-week-of-symfony',
        ]);
    }
}
