<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = [
            [
                'name' => 'Escola Municipal Ana Melo',
                'institution_id' => 1, // IFPE Campus Afogados
                'description' => 'Escola municipal localizada em Afogados da Ingazeira'
            ],
            [
                'name' => 'Escola Municipal Gizelda Simões',
                'institution_id' => 1, // IFPE Campus Afogados
                'description' => 'Escola municipal localizada em Afogados da Ingazeira'
            ],
            [
                'name' => 'Escola Luíz Bezerra de Mello',
                'institution_id' => 2, // IFPE Campus Barreiros
                'description' => 'Escola localizada em Barreiros'
            ],
            [
                'name' => 'EM Prof Inês Barbosa de Andrade',
                'institution_id' => 3, // IFPE Campus Belo Jardim
                'description' => 'Escola municipal localizada em Belo Jardim'
            ]
        ];

        foreach ($schools as $schoolData) {
            School::create([
                'name' => $schoolData['name'],
                'institution_id' => $schoolData['institution_id'],
                'slug' => \Illuminate\Support\Str::slug($schoolData['name'])
            ]);
        }
    }
}
