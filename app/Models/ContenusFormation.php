<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContenusFormation extends Model
{
    use HasFactory;

    protected $fillable = [
        'nomchap',
        'nomunite',
        'description',
        'nombreheures',
        'formation_id'
    ];

    public function formation()
    {
        return $this->belongsTo(Formations::class, 'formation_id');
    }
}
