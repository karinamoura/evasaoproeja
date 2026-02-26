<?php

namespace Database\Factories;

use App\Models\Questionario;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Questionario>
 */
class QuestionarioFactory extends Factory
{
    protected $model = Questionario::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titulo' => $this->faker->sentence(3),
            'descricao' => $this->faker->paragraph(2),
            'slug' => $this->faker->unique()->slug,
            'ativo' => $this->faker->boolean(80), // 80% chance de estar ativo
        ];
    }
}
