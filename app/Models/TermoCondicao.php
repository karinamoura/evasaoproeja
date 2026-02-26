<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TermoCondicao extends Model
{
    use HasFactory;

    protected $table = 'termos_condicoes';

    protected $fillable = [
        'titulo',
        'conteudo',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean'
    ];

    /**
     * Relacionamento com questionários ofertas
     */
    public function questionarioOfertas()
    {
        return $this->hasMany(QuestionarioOferta::class);
    }
}
