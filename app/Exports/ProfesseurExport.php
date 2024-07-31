<?php

namespace App\Exports;

use App\Models\Professeur;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProfesseurExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Professeur::with('country','type')
            ->get()
            ->map(function ($prof) {
                return [
                    'ID' => $prof->id,
                    'Nom & Prénom' => $prof->nomprenom,
                    'Nationalité' => $prof->country ? $prof->country->name : '',
                    'Types de contrats' => $prof->type ? $prof->type->type : '',
                    'Diplôme' => $prof->diplome,
                    'Genre' => $prof->genre,
                    'Lieu de naissance' => $prof->lieunaissance,
                    'Adresse' => $prof->adress,
                    'Age' => $prof->age,
                    'Email' => $prof->email,
                    'Portable' => $prof->phone,
                    'WhatsApp' => $prof->wtsp,


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
            'Nom & Prénom',
            'Nationalité',
            'Type de contrat',
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
