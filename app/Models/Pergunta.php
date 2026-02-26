<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pergunta extends Model
{
    use HasFactory;

    protected $fillable = [
        'questionario_id',
        'secao_id',
        'pergunta',
        'tipo',
        'obrigatoria',
        'ordem',
        'formato_validacao'
    ];

    protected $casts = [
        'obrigatoria' => 'boolean'
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
     * Relacionamento com questionário
     */
    public function questionario()
    {
        return $this->belongsTo(Questionario::class);
    }

    /**
     * Relacionamento com seção
     */
    public function secao()
    {
        return $this->belongsTo(Secao::class);
    }

    /**
     * Relacionamento com opções de resposta
     */
    public function opcoesResposta()
    {
        return $this->hasMany(OpcaoResposta::class)->orderBy('ordem');
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
