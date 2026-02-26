<?php

namespace Database\Seeders;

use App\Models\Oferta;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OfertaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Não criar ofertas via factory - apenas dados essenciais
        // As ofertas serão criadas pelos usuários através da interface
    }
}
