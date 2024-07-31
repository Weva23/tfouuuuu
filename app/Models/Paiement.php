<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = ['etudiant_id', 'session_id', 'mode_paiement_id', 'prix_reel','note_test', 'montant_paye', 'date_paiement'];

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function session()
    {
        return $this->belongsTo(Sessions::class);
    }

    public function mode()
    {
        return $this->belongsTo(ModePaiement::class, 'mode_paiement_id');
    }
}
