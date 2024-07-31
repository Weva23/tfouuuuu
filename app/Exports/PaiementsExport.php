<?php

namespace App\Exports;

use App\Models\Paiement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PaiementsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Paiement::with(['etudiant', 'session.formation', 'mode'])
            ->join('etudiants', 'paiements.etudiant_id', '=', 'etudiants.id')
            ->join('sessions', 'paiements.session_id', '=', 'sessions.id')
            ->orderBy('sessions.nom', 'asc')
            ->orderBy('etudiants.nomprenom', 'asc')
            ->select('paiements.*')
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
            'Formation',
            'Prix Réel',
            'Montant Payé',
            'Mode de Paiement',
            'Reste à Payer',
            'Date de Paiement',
        ];
    }

    public function map($paiement): array
    {
        $montantPayeTotal = Paiement::where('etudiant_id', $paiement->etudiant_id)
            ->where('session_id', $paiement->session_id)
            ->sum('montant_paye');
        $resteAPayer = $paiement->prix_reel - $montantPayeTotal;

        return [
            // $paiement->id,
            $paiement->etudiant->nomprenom ?? 'N/A',
            $paiement->etudiant->phone ?? 'N/A',
            $paiement->etudiant->wtsp ?? 'N/A',
            $paiement->session->formation->nom ?? 'N/A',
            $paiement->session->nom ?? 'N/A',
            $paiement->prix_reel,
            $paiement->montant_paye,
            $paiement->mode->nom ?? 'N/A',
            $resteAPayer,
            $paiement->date_paiement,
        ];
    }
}
