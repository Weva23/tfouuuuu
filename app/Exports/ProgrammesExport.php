<?php

namespace App\Exports;

// use App\Models\Formations;
use App\Models\Programmes;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProgrammesExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Programmes::all(['id', 'code', 'Nom']);
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
            
            // Ajoutez d'autres noms de colonnes selon votre modèle
        ];
    }
}

