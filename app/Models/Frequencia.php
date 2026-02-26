<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Frequencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'estudante_id',
        'disciplina_id',
        'data_aula',
        'hora_aula',
        'observacoes',
    ];

    protected $casts = [
        'data_aula' => 'date',
    ];

    public function estudante()
    {
        return $this->belongsTo(Estudante::class, 'estudante_id');
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class, 'disciplina_id');
    }
}
