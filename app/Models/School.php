<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $table = 'schools';

    protected $fillable = [
        'name','slug','institution_id'
    ];

    public function institution()
    {
        return $this->belongsTo(Institution::class,'institution_id');
    }
}
