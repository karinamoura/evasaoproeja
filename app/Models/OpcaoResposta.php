<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpcaoResposta extends Model
{
    use HasFactory;

    protected $table = 'opcoes_resposta';

    protected $fillable = [
        'pergunta_id',
        'opcao',
        'ordem'
    ];

    /**
     * Relacionamento com pergunta
     */
    public function pergunta()
    {
        return $this->belongsTo(Pergunta::class);
    }
}
