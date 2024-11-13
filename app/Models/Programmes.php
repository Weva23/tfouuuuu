<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programmes extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'nom'
    ];

    public function formations()
    {
        return $this->hasMany(Formations::class, 'programme_id');
    }
}
