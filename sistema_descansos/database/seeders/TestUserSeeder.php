<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class TestUserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate([
            'email' => 'test@example.com',
        ], [
            'name' => 'Usuario de Prueba',
            'password' => 'secret123',
        ]);

        $this->command->info('Test user seeded: test@example.com / secret123');
    }
}
