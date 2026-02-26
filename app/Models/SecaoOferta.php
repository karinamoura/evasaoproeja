<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecaoOferta extends Model
{
    use HasFactory;

    protected $table = 'secoes_oferta';

    protected $fillable = [
        'questionario_oferta_id',
        'titulo',
        'descricao',
        'ordem',
    ];

    public function questionarioOferta()
    {
        return $this->belongsTo(QuestionarioOferta::class);
    }

    public function perguntas()
    {
        return $this->hasMany(PerguntaOferta::class, 'secao_oferta_id')->orderBy('ordem');
    }
}


