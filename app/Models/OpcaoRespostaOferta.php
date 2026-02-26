<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpcaoRespostaOferta extends Model
{
    use HasFactory;

    protected $table = 'opcao_resposta_oferta';

    protected $fillable = [
        'pergunta_oferta_id',
        'opcao',
        'ordem'
    ];

    /**
     * Relacionamento com pergunta da oferta
     */
    public function perguntaOferta()
    {
        return $this->belongsTo(PerguntaOferta::class);
    }
}
