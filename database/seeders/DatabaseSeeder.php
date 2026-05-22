<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@directorio-pamplona.co',
            'password' => bcrypt('password'),
        ]);

        $this->call(CategorySeeder::class);

        Business::factory(100)->create();
    }
}
