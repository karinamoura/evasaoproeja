<?php

namespace Database\Factories;

use App\Models\OpcaoResposta;
use App\Models\Pergunta;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OpcaoResposta>
 */
class OpcaoRespostaFactory extends Factory
{
    protected $model = OpcaoResposta::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $opcoes = [
            'Concordo totalmente',
            'Concordo',
            'Neutro',
            'Discordo',
            'Discordo totalmente',
            'Sim',
            'Não',
            'Talvez',
            'Excelente',
            'Bom',
            'Regular',
            'Ruim',
            'Muito ruim'
        ];

        return [
            'pergunta_id' => Pergunta::factory(),
            'opcao' => $this->faker->randomElement($opcoes),
            'ordem' => $this->faker->numberBetween(1, 5),
        ];
    }
}
