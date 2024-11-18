<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sessions extends Model
{
    use HasFactory;

    protected $fillable = ['formation_id', 'nom', 'date_debut', 'date_fin','created_by'];

    public function professeurs()
    {
        return $this->belongsToMany(Professeur::class, 'prof_session', 'session_id', 'prof_id')->withPivot('date_paiement')->withTimestamps();
    }

    public function formation()
    {
        return $this->belongsTo(Formations::class);
    }

    public function etudiants()
    {
        return $this->belongsToMany(Etudiant::class, 'etud_session', 'session_id', 'etudiant_id')->withPivot('date_paiement')->withTimestamps();
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function paiementprofs()
    {
        return $this->hasMany(PaiementProf::class);
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
