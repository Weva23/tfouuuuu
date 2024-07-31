<?php

namespace App\Exports;

use App\Models\ContenusFormation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ContenusFormationExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ContenusFormation::with('formation')
            ->get()
            ->map(function ($contenue) {
                return [
                    'ID' => $contenue->id,
                    'Formation' => $contenue->formation ? $contenue->formation->nom : '',
                    'Nom du chapitre' => $contenue->nomchap,
                    'Nom de l\'unite' => $contenue->nomunite,
                    'Nombre d\'heuren' => $contenue->nombreheures,
                    'Description' => $contenue->description,
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
            'Formation',
            'Nom du chapitre',
            'Nom de l\'unite',
            'Nombre d\'heuren',
            'Description',
            // Ajoutez d'autres noms de colonnes selon votre modèle
        ];
    }
}
