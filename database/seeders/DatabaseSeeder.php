<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PermissionSeeder::class);
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(InstitutionSeeder::class);
        $this->call(SchoolSeeder::class);
        $this->call(OfertaSeeder::class);
        $this->call(QuestionarioSeeder::class);
    }
}
