<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionarioOferta extends Model
{
    use HasFactory;

    protected $fillable = [
        'questionario_id',
        'oferta_id',
        'titulo_personalizado',
        'descricao_personalizada',
        'cor_personalizada',
        'termo_condicao_id',
        'ativo',
        'url_publica',
        'pergunta_identificadora_id'
    ];

    protected $casts = [
        'ativo' => 'boolean'
    ];

    /**
     * Relacionamento com questionário base
     */
    public function questionario()
    {
        return $this->belongsTo(Questionario::class);
    }

    /**
     * Relacionamento com oferta
     */
    public function oferta()
    {
        return $this->belongsTo(Oferta::class);
    }

    /**
     * Relacionamento com perguntas personalizadas
     */
    public function perguntas()
    {
        return $this->hasMany(PerguntaOferta::class);
    }

    /**
     * Obter perguntas ordenadas
     */
    public function perguntasOrdenadas()
    {
        return $this->perguntas()->orderBy('ordem', 'asc');
    }

    /**
     * Relacionamento com seções da oferta
     */
    public function secoes()
    {
        return $this->hasMany(SecaoOferta::class)->orderBy('ordem');
    }

    /**
     * Relacionamento com respostas
     */
    public function respostas()
    {
        return $this->hasMany(RespostaQuestionario::class);
    }

    /**
     * Relacionamento com termo de condições
     */
    public function termoCondicao()
    {
        return $this->belongsTo(TermoCondicao::class, 'termo_condicao_id');
    }

    /**
     * Obter título (personalizado ou padrão)
     */
    public function getTituloAttribute()
    {
        return $this->titulo_personalizado ?: $this->questionario->titulo;
    }

    /**
     * Obter descrição (personalizada ou padrão)
     */
    public function getDescricaoAttribute()
    {
        return $this->descricao_personalizada ?: $this->questionario->descricao;
    }

    /**
     * Gerar URL pública única
     */
    public static function gerarUrlPublica()
    {
        $baseUrl = 'questionario-' . \Illuminate\Support\Str::random(10);
        $uniqueUrl = $baseUrl;
        $counter = 1;

        while (static::where('url_publica', $uniqueUrl)->exists()) {
            $uniqueUrl = $baseUrl . '-' . $counter;
            $counter++;
        }

        return $uniqueUrl;
    }

    /**
     * Relacionamento com pergunta identificadora
     */
    public function perguntaIdentificadora()
    {
        return $this->belongsTo(PerguntaOferta::class, 'pergunta_identificadora_id');
    }

    /**
     * Retorna a pergunta que contém o campo CPF (identificadora se for CPF, ou primeira pergunta com formato CPF).
     */
    public function getPerguntaCpf(): ?PerguntaOferta
    {
        $identificadora = $this->perguntaIdentificadora;
        if ($identificadora && $identificadora->formato_validacao === 'cpf') {
            return $identificadora;
        }
        return $this->perguntas()->where('formato_validacao', 'cpf')->orderBy('ordem')->first();
    }

    /**
     * Verifica se o questionário ofertado possui algum campo CPF (para cruzamento com estudante/frequência).
     */
    public function temCampoCpf(): bool
    {
        return $this->getPerguntaCpf() !== null;
    }
}
