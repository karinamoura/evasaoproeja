<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespostaIndividual extends Model
{
    use HasFactory;

    protected $table = 'respostas_individual';

    protected $fillable = [
        'resposta_questionario_id',
        'pergunta_oferta_id',
        'resposta_texto',
        'resposta_multipla',
        'resposta_unica'
    ];

    protected $casts = [
        'resposta_multipla' => 'array'
    ];

    /**
     * Relacionamento com resposta do questionário
     */
    public function respostaQuestionario()
    {
        return $this->belongsTo(RespostaQuestionario::class);
    }

    /**
     * Relacionamento com pergunta da oferta
     */
    public function perguntaOferta()
    {
        return $this->belongsTo(PerguntaOferta::class);
    }

    /**
     * Obter resposta formatada
     */
    public function getRespostaFormatadaAttribute()
    {
        if ($this->resposta_texto) {
            return $this->resposta_texto;
        }

        if ($this->resposta_multipla) {
            return implode(', ', $this->resposta_multipla);
        }

        if ($this->resposta_unica) {
            return $this->resposta_unica;
        }

        return 'Sem resposta';
    }
}
