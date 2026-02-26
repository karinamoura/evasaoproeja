<?php

namespace App\Models;

use App\Helpers\CpfHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespostaQuestionario extends Model
{
    use HasFactory;

    protected $table = 'respostas_questionario';

    protected $fillable = [
        'questionario_oferta_id',
        'identificador_respondente',
        'data_resposta'
    ];

    protected $casts = [
        'data_resposta' => 'datetime'
    ];

    /**
     * Relacionamento com questionário da oferta
     */
    public function questionarioOferta()
    {
        return $this->belongsTo(QuestionarioOferta::class);
    }

    /**
     * Relacionamento com respostas individuais
     */
    public function respostasIndividuais()
    {
        return $this->hasMany(RespostaIndividual::class);
    }

    /**
     * Boot do modelo
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->data_resposta)) {
                $model->data_resposta = now();
            }
        });
    }

    /**
     * Retorna o valor de CPF informado nesta resposta (se o questionário tiver campo CPF).
     */
    public function getCpfRespostaAttribute(): ?string
    {
        $questionarioOferta = $this->questionarioOferta;
        if (!$questionarioOferta) {
            return null;
        }

        $perguntaCpf = $questionarioOferta->getPerguntaCpf();
        if (!$perguntaCpf) {
            return null;
        }

        if ($questionarioOferta->pergunta_identificadora_id == $perguntaCpf->id) {
            return $this->identificador_respondente;
        }

        $respostaCpf = $this->respostasIndividuais
            ->where('pergunta_oferta_id', $perguntaCpf->id)
            ->first();

        return $respostaCpf?->resposta_texto;
    }

    /**
     * Estudante vinculado pelo CPF (mesma oferta do questionário), quando o questionário tem campo CPF.
     */
    public function getEstudanteVinculadoAttribute(): ?Estudante
    {
        $cpf = $this->cpf_resposta;
        if ($cpf === null || $cpf === '') {
            return null;
        }

        $digitos = CpfHelper::apenasDigitos($cpf);
        if (!CpfHelper::cpfValidoParaCruzamento($digitos)) {
            return null;
        }

        $questionarioOferta = $this->questionarioOferta;
        if (!$questionarioOferta || !$questionarioOferta->oferta_id) {
            return null;
        }

        $estudantes = Estudante::where('oferta_id', $questionarioOferta->oferta_id)->get();
        foreach ($estudantes as $estudante) {
            if (CpfHelper::apenasDigitos($estudante->cpf) === $digitos) {
                return $estudante;
            }
        }

        return null;
    }
}
