<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programmes extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'nom','created_by'
    ];

    public function formations()
    {
        return $this->hasMany(Formations::class, 'programme_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
}
