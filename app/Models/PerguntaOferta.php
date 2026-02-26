<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerguntaOferta extends Model
{
    use HasFactory;

    protected $table = 'pergunta_oferta';

    protected $fillable = [
        'questionario_oferta_id',
        'secao_oferta_id',
        'pergunta',
        'tipo',
        'obrigatoria',
        'ordem',
        'personalizada',
        'formato_validacao'
    ];

    protected $casts = [
        'obrigatoria' => 'boolean',
        'personalizada' => 'boolean'
    ];

    /**
     * Tipos de pergunta disponíveis
     */
    const TIPOS = [
        'texto_simples' => 'Texto Simples',
        'texto_longo' => 'Texto Longo',
        'radio' => 'Única Escolha (Radio)',
        'checkbox' => 'Múltipla Escolha (Checkbox)',
        'select' => 'Seleção (Select)'
    ];

    /**
     * Formatos de validação disponíveis
     */
    const FORMATOS_VALIDACAO = [
        'texto_comum' => 'Texto Comum',
        'data' => 'Data',
        'cpf' => 'CPF',
        'telefone' => 'Telefone',
        'email' => 'E-mail'
    ];

    /**
     * Relacionamento com questionário da oferta
     */
    public function questionarioOferta()
    {
        return $this->belongsTo(QuestionarioOferta::class);
    }

    public function secaoOferta()
    {
        return $this->belongsTo(SecaoOferta::class);
    }

    /**
     * Relacionamento com opções de resposta
     */
    public function opcoesResposta()
    {
        return $this->hasMany(OpcaoRespostaOferta::class)->orderBy('ordem');
    }

    /**
     * Verificar se a pergunta tem opções de resposta
     */
    public function temOpcoes()
    {
        return in_array($this->tipo, ['radio', 'checkbox', 'select']);
    }

    /**
     * Verificar se é pergunta de texto
     */
    public function isTexto()
    {
        return in_array($this->tipo, ['texto_simples', 'texto_longo']);
    }

    /**
     * Verificar se é pergunta de múltipla escolha
     */
    public function isMultiplaEscolha()
    {
        return $this->tipo === 'checkbox';
    }
}
