<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Formations extends Model
{
    use HasFactory;

    protected $fillable = [
       'programme_id', 'code', 'nom', 'duree', 'prix','created_by',
    ];

    public function sessions()
    {
        return $this->hasMany(Sessions::class, 'formation_id');
    }
    public function programme()
    {
        return $this->belongsTo(Programmes::class);
    }

    public function contenusFormation()
    {
        return $this->hasMany(ContenusFormation::class, 'formation_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
