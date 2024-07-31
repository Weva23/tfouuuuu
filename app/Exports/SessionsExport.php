<?php
namespace App\Exports;

use App\Models\Sessions;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SessionsExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Sessions::all(['id', 'formation_id', 'date_debut', 'date_fin', 'nom']);
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
            'Date de début',
            'Date de fin',
            'Nom',
        ];
    }
}
