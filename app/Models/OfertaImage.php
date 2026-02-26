<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfertaImage extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'oferta_images';

    protected $fillable = [
        'oferta_id',
        'image',
    ];

    public function oferta()
    {
        return $this->belongsTo(Oferta::class,'oferta_id');
    }

}
