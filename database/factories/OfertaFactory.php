<?php

namespace Database\Factories;

use App\Models\Oferta;
use App\Models\Institution;
use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Oferta>
 */
class OfertaFactory extends Factory
{
    protected $model = Oferta::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->sentence(3),
            'slug' => $this->faker->slug,

            'institution_id' => Institution::factory(),
            'school_id' => School::factory(),
            'codigo_sistema_academico' => $this->faker->regexify('[A-Z]{2}[0-9]{6}'),
            'turma' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'E']),
            'nome_curso' => $this->faker->randomElement(['Administração', 'Ciência da Computação', 'Engenharia Civil', 'Medicina', 'Direito']),
            'ano_letivo' => $this->faker->randomElement(['2024', '2025', '2026']),
            'periodo_letivo' => $this->faker->randomElement(['1º Semestre', '2º Semestre', 'Anual']),
        ];
    }
}
