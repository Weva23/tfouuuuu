<?php

namespace App\Exports;

use App\Models\PaiementProf;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PaiementProfsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return PaiementProf::with(['professeur', 'session.formation', 'mode', 'type'])
            ->join('professeurs', 'paiement_profs.prof_id', '=', 'professeurs.id')
            ->join('sessions', 'paiement_profs.session_id', '=', 'sessions.id')
            ->orderBy('sessions.nom', 'asc')
            ->orderBy('professeurs.nomprenom', 'asc')
            ->select('paiement_profs.*')
            ->get();
    }

    public function headings(): array
    {
        return [
            // 'ID',
            'Nom & Prénom',
            'Portable',
            'WhatsApp',
            'Programme',
            'Session',
            'Type de Paiement',
            'Montant à Payer',
            'Montant Payé',
            'Mode de Paiement',
            'Reste à Payer',
            'Date de Paiement',
        ];
    }

    public function map($paiementprof): array
    {
        $montantPayeTotal = PaiementProf::where('prof_id', $paiementprof->prof_id)
            ->where('session_id', $paiementprof->session_id)
            ->sum('montant_paye');
        $resteAPayer = $paiementprof->montant_a_paye - $montantPayeTotal;

        return [
            // $paiement->id,
            $paiementprof->professeur->nomprenom ?? 'N/A',
            $paiementprof->professeur->phone ?? 'N/A',
            $paiementprof->professeur->wtsp ?? 'N/A',
            $paiementprof->session->formation->nom ?? 'N/A',
            $paiementprof->session->nom ?? 'N/A',
            $paiementprof->type->type ?? 'N/A',
            $paiementprof->montant_a_paye,
            $paiementprof->montant_paye,
            $paiementprof->mode->nom ?? 'N/A',
            $resteAPayer,
            $paiementprof->date_paiement,
        ];
    }
}
