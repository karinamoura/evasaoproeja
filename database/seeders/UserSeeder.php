<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar usuários específicos sem usar factory
        $admin = \App\Models\User::create([
            'name' => 'Admin',
            'email' => 'admin@evasao.com',
            'password' => bcrypt('123456'),
        ]);
        $admin->assignRole('admin');

        $pedagogico = \App\Models\User::create([
            'name' => 'Coordenador',
            'email' => 'coordenador@evasao.com',
            'password' => bcrypt('123456'),
        ]);
        $pedagogico->assignRole('pedagogico');

        $user = \App\Models\User::create([
             'name' => 'Professor',
             'email' => 'professor@evasao.com',
             'password' => bcrypt('123456'),
        ]);
        $user->assignRole('user');
    }
}
