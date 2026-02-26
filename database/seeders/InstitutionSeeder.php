<?php

namespace Database\Seeders;

use App\Models\Institution;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InstitutionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $institutions = [
            'IFPE Campus Afogados',
            'IFPE Campus Barreiros',
            'IFPE Campus Belo Jardim',
            'IFPE Campus Palmares',
            'IFPE Campus Igarassu'
        ];

        foreach ($institutions as $institutionName) {
            Institution::create([
                'name' => $institutionName,
                'slug' => \Illuminate\Support\Str::slug($institutionName)
            ]);
        }
    }
}
