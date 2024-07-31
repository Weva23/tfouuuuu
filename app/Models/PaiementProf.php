<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaiementProf extends Model
{
    use HasFactory;

    protected $fillable = [
        'prof_id', 'session_id', 'mode_paiement_id', 'typeymntprofs_id', 'montant', 'montant_a_paye', 'montant_paye', 'date_paiement'
    ];

    public function updateMontantAPaye($totalStudentPayments)
    {
        if ($this->typeymntprofs_id == 1) {
            $this->montant_a_paye = ($totalStudentPayments * $this->montant) / 100;
        } else if ($this->typeymntprofs_id == 2) {
            // Assuming monthly salary
            $this->montant_a_paye = $this->montant;
        } else if ($this->typeymntprofs_id == 3) {
            // Assuming hourly rate
            $this->montant_a_paye = $this->montant;
        }
        $this->save();
    }
    

    public function professeur()
    {
        return $this->belongsTo(Professeur::class, 'prof_id');
    }

    public function session()
    {
        return $this->belongsTo(Sessions::class);
    }

    public function mode()
    {
        return $this->belongsTo(ModePaiement::class, 'mode_paiement_id');
    }

    public function type()
    {
        return $this->belongsTo(Typeymntprofs::class, 'typeymntprofs_id');
    }
}
