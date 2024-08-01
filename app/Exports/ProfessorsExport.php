<?php
namespace App\Exports;

use App\Models\Sessions;
use App\Models\Professeur;
use App\Models\PaiementProf;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProfessorsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Sessions::with(['professeurs.paiementprofs.mode'])->get()->flatMap(function ($session) {
            return $session->professeurs->map(function ($professeur) use ($session) {
                $paiementProf = $professeur->paiementprofs->where('session_id', $session->id)->first();
                return [
                    'Session' => $session->nom,
                    'Programme' => $session->formation->nom,
                    'Nom & Prénom' => $professeur->nomprenom,
                    'Phone' => $professeur->phone,
                    'WhatsApp' => $professeur->wtsp,
                    'Montant' => $paiementProf ? $paiementProf->montant : '',
                    'Montant à Payer' => $paiementProf ? $paiementProf->montant_a_paye : '',
                    'Montant Payé' => $paiementProf ? $paiementProf->montant_paye : '',
                    'Reste à Payer' => $paiementProf ? $paiementProf->montant_a_paye - $paiementProf->montant_paye : '',
                ];
            });
        });
    }

    public function headings(): array
    {
        return [
            'Session',
            'Programme',
            'Nom & Prénom',
            'Phone',
            'WhatsApp',
            'Montant',
            'Montant à Payer',
            'Montant Payé',
            'Reste à Payer',
        ];
    }
}
