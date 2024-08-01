<?php
namespace App\Exports;

use App\Models\Etudiant;
use App\Models\Paiement;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class StudentsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function collection()
    {
        return Etudiant::with(['paiements.session.formation', 'paiements.mode'])->get();
    }

    public function headings(): array
    {
        return [
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

    public function map($etudiant): array
    {
        $data = [];

        foreach ($etudiant->paiements as $paiement) {
            $montantPayeTotal = Paiement::where('etudiant_id', $etudiant->id)
                ->where('session_id', $paiement->session_id)
                ->sum('montant_paye');
            $resteAPayer = $paiement->prix_reel - $montantPayeTotal;

            $data[] = [
                $etudiant->nomprenom ?? 'N/A',
                $etudiant->phone ?? 'N/A',
                $etudiant->wtsp ?? 'N/A',
                $paiement->session->formation->nom ?? 'N/A',
                $paiement->session->nom ?? 'N/A',
                $paiement->prix_reel,
                $paiement->montant_paye,
                $paiement->mode->nom ?? 'N/A',
                $resteAPayer,
                $paiement->date_paiement,
            ];
        }

        return $data;
    }
}