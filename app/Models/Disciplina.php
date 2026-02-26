<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disciplina extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'professor_id',
        'oferta_id',
        'periodo',
        'carga_horaria_total',
        'data_inicio',
        'data_fim',
    ];

    protected $casts = [
        'data_inicio' => 'date',
        'data_fim' => 'date',
    ];

    public function professor()
    {
        return $this->belongsTo(User::class, 'professor_id');
    }

    public function oferta()
    {
        return $this->belongsTo(Oferta::class, 'oferta_id');
    }

    public function estudantes()
    {
        return $this->belongsToMany(Estudante::class, 'estudante_disciplina')
                    ->withTimestamps();
    }

    public function frequencias()
    {
        return $this->hasMany(Frequencia::class);
    }

    /**
     * Calcula o percentual de tempo decorrido da disciplina
     */
    public function getPercentualTempoDecorrido()
    {
        if (!$this->data_inicio || !$this->data_fim) {
            return 0; // Se não tiver datas, retorna 0 (não mostra alertas)
        }

        $hoje = \Carbon\Carbon::now()->startOfDay();
        $inicio = \Carbon\Carbon::parse($this->data_inicio)->startOfDay();
        $fim = \Carbon\Carbon::parse($this->data_fim)->startOfDay();

        // Se ainda não começou
        if ($hoje < $inicio) {
            return 0;
        }

        // Se já terminou
        if ($hoje >= $fim) {
            return 100;
        }

        // Calcular percentual
        $totalDias = $inicio->diffInDays($fim);
        $diasDecorridos = $inicio->diffInDays($hoje);

        if ($totalDias == 0) {
            return 100;
        }

        return ($diasDecorridos / $totalDias) * 100;
    }
}
