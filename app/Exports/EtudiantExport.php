<?php

namespace App\Exports;

use App\Models\Etudiant;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class EtudiantExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Etudiant::with('country')
            ->get()
            ->map(function ($etudiant) {
                return [
                    'ID' => $etudiant->id,
                    'NNI' => $etudiant->nni,
                    'Nom & Prénom' => $etudiant->nomprenom,
                    'Nationalité' => $etudiant->country ? $etudiant->country->name : '',
                    'Diplôme' => $etudiant->diplome,
                    'Genre' => $etudiant->genre,
                    'Lieu de naissance' => $etudiant->lieunaissance,
                    'Adresse' => $etudiant->adress,
                    'Age' => $etudiant->age,
                    'Email' => $etudiant->email,
                    'Portable' => $etudiant->phone,
                    'WhatsApp' => $etudiant->wtsp,
                ];
            });
    }

    /**
     * Retourne les en-têtes des colonnes.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'NNI',
            'Nom & Prénom',
            'Nationalité',
            'Diplôme',
            'Genre',
            'Lieu Naissance',
            'Adresse',
            'Age',
            'Email',
            'Portable',
            'WhatsApp',
            // Ajoutez d'autres noms de colonnes selon votre modèle
        ];
    }
}
