<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudante extends Model
{
    use HasFactory;

    protected $table = 'estudantes';

    protected $fillable = [
        'nome',
        'cpf',
        'data_nascimento',
        'matricula',
        'nome_mae',
        'cep',
        'telefone',
        'email',
        'oferta_id',
    ];

    protected $casts = [
        'data_nascimento' => 'date',
    ];

    public function oferta()
    {
        return $this->belongsTo(Oferta::class, 'oferta_id');
    }

    public function disciplinas()
    {
        return $this->belongsToMany(Disciplina::class, 'estudante_disciplina')
                    ->withTimestamps();
    }

    public function frequencias()
    {
        return $this->hasMany(Frequencia::class);
    }

    /**
     * Calcula a frequência total do estudante em uma disciplina
     */
    public function getFrequenciaTotal($disciplinaId)
    {
        return $this->frequencias()
                    ->where('disciplina_id', $disciplinaId)
                    ->sum('hora_aula');
    }
}

