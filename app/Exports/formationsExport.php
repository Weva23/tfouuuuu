<?php

namespace App\Exports;

use App\Models\Formations;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class formationsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Formations::all(['id', 'code', 'Nom', 'duree', 'prix']);
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
            'code',
            'Nom',
            'duree',
            'prix',
            
            // Ajoutez d'autres noms de colonnes selon votre modèle
        ];
    }
}

