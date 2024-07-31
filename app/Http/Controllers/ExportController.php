<?php

namespace App\Http\Controllers;

use App\Exports\ProfesseurExport;
use App\Exports\EtudiantExport;
use App\Exports\formationsExport;
use App\Exports\ContenusFormationExport;
use App\Exports\SessionsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function exportProfesseurs()
    {
        return Excel::download(new ProfesseurExport, 'professeurs.xlsx');
    }

    public function exportEtudiants()
    {
        return Excel::download(new EtudiantExport, 'etudiants.xlsx');
    }
    public function formationsExport()
    {
        return Excel::download(new formationsExport, 'programmes.xlsx');
    }
    public function exportContenusFormation()
    {
        return Excel::download(new ContenusFormationExport, 'ContenusFormation.xlsx');
    }
    public function exportSessions()
    {
        return Excel::download(new SessionsExport, 'sessions.xlsx');
    }
}
