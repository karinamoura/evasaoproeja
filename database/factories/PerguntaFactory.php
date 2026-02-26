<?php

namespace Database\Factories;

use App\Models\Pergunta;
use App\Models\Questionario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pergunta>
 */
class PerguntaFactory extends Factory
{
    protected $model = Pergunta::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tipos = ['texto_simples', 'texto_longo', 'radio', 'checkbox', 'select'];

        return [
            'questionario_id' => Questionario::factory(),
            'pergunta' => $this->faker->sentence(6) . '?',
            'tipo' => $this->faker->randomElement($tipos),
            'obrigatoria' => $this->faker->boolean(70), // 70% chance de ser obrigatória
            'ordem' => $this->faker->numberBetween(1, 10),
        ];
    }
}
