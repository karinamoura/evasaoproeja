<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Oferta extends Model
{
    use HasFactory;

    protected $table = 'ofertas';

    protected $fillable = [
        'name',
        'slug',
        'institution_id',
        'school_id',
        'turno',
        'coordenador_id',
        'codigo_sistema_academico',
        'turma',
        'nome_curso',
        'ano_letivo',
        'periodo_letivo',
        'responsavel_transporte_estudante',
        'oferta_auxilio_financeiro',
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class,'institution_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class,'school_id');
    }

    public function coordenador()
    {
        return $this->belongsTo(User::class,'coordenador_id');
    }



    /**
     * Relacionamento com questionários
     */
    public function questionarios()
    {
        return $this->belongsToMany(Questionario::class, 'questionario_oferta')
                    ->withPivot(['titulo_personalizado', 'descricao_personalizada', 'ativo', 'url_publica'])
                    ->withTimestamps();
    }

    /**
     * Relacionamento direto com questionario_oferta
     */
    public function questionarioOfertas()
    {
        return $this->hasMany(QuestionarioOferta::class);
    }

    /**
     * Relacionamento com disciplinas
     */
    public function disciplinas()
    {
        return $this->hasMany(Disciplina::class);
    }

    /**
     * Relacionamento com estudantes
     */
    public function estudantes()
    {
        return $this->hasMany(Estudante::class);
    }
}
