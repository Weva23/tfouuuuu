<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professeur extends Model
{
    use HasFactory;

    protected $fillable = [
        'image', 'nomprenom', 'diplome', 'genre', 'lieunaissance', 'adress', 'datenaissance','dateninscrip', 'email', 'phone', 'wtsp', 'country_id', 'type_id','created_by'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function type()
    {
        return $this->belongsTo(Typeymntprofs::class, 'type_id');
    }

    public function sessions()
    {
        return $this->belongsToMany(Sessions::class, 'prof_session', 'prof_id', 'session_id')->withPivot('date_paiement')->withTimestamps();
    }

    public function paiementprofs()
    {
        return $this->hasMany(PaiementProf::class, 'prof_id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
