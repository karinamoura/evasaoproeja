<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Secao extends Model
{
    use HasFactory;

    protected $table = 'secoes';

    protected $fillable = [
        'questionario_id',
        'titulo',
        'descricao',
        'ordem',
    ];

    public function questionario()
    {
        return $this->belongsTo(Questionario::class);
    }

    public function perguntas()
    {
        return $this->hasMany(Pergunta::class)->orderBy('ordem');
    }
}


