<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questionario extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descricao',
        'slug',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean'
    ];

    /**
     * Relacionamento com perguntas
     */
    public function perguntas()
    {
        return $this->hasMany(Pergunta::class)->orderBy('ordem');
    }

    /**
     * Relacionamento com seções
     */
    public function secoes()
    {
        return $this->hasMany(Secao::class)->orderBy('ordem');
    }

    /**
     * Relacionamento com ofertas
     */
    public function ofertas()
    {
        return $this->belongsToMany(Oferta::class, 'questionario_oferta')
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
     * Gerar slug único
     */
    public static function gerarSlug($titulo)
    {
        $baseSlug = \Illuminate\Support\Str::slug($titulo);
        $uniqueSlug = $baseSlug;
        $counter = 1;

        while (static::where('slug', $uniqueSlug)->exists()) {
            $uniqueSlug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $uniqueSlug;
    }
}
