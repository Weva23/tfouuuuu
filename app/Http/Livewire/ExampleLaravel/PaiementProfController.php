<?php

namespace App\Http\Livewire\ExampleLaravel;

use Illuminate\Http\Request;
use Livewire\Component;
use App\Models\PaiementProf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaiementProfsExport;
use Carbon\Carbon;

class PaiementProfController extends Component
{
    public function list_paiementprof()
    {
        $paiementprofs = PaiementProf::with(['professeur', 'type', 'mode', 'session.formation'])
            ->join('professeurs', 'paiement_profs.prof_id', '=', 'professeurs.id')
            ->join('sessions', 'paiement_profs.session_id', '=', 'sessions.id')
            ->orderBy('sessions.nom', 'asc')
            ->orderBy('professeurs.nomprenom', 'asc')
            ->select('paiement_profs.*')
            ->paginate(8);

        foreach ($paiementprofs as $paiement) {
            $montantPayeTotal = PaiementProf::where('prof_id', $paiement->prof_id)
                ->where('session_id', $paiement->session_id)
                ->sum('montant_paye');
            $paiement->reste_a_payer = $paiement->montant_a_paye - $montantPayeTotal;
        }

        return view('livewire.example-laravel.paiementprof-management', compact('paiementprofs'));
    }

    public function render()
    {
        return $this->list_paiementprof();
    }

    public function exportPaiementProfs()
    {
        return Excel::download(new PaiementProfsExport, 'paiementprofs.xlsx');
    }

    public function searchPaymentProfs(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->search;
            $paiementprofs = PaiementProf::with(['professeur', 'session', 'type', 'mode'])
                ->where('montant_paye', 'like', "%$search%")
                ->orWhere('montant_a_paye', 'like', "%$search%")
                ->orWhereHas('professeur', function ($query) use ($search) {
                    $query->where('nomprenom', 'like', "%$search%")
                          ->orWhere('phone', 'like', "%$search%")
                          ->orWhere('wtsp', 'like', "%$search%");
                })
                ->orWhereHas('session', function ($query) use ($search) {
                    $query->where('nom', 'like', "%$search%");
                })
                ->orWhereHas('type', function ($query) use ($search) {
                    $query->where('type', 'like', "%$search%");
                })
                ->orWhereHas('mode', function ($query) use ($search) {
                    $query->where('nom', 'like', "%$search%");
                })
                ->paginate(10);

            $view = view('livewire.example-laravel.paiementprofs-list', compact('paiementprofs'))->render();
            return response()->json(['html' => $view]);
        }
    }

    public function generateReceiptProf($paiementId)
    {
        $paiement = PaiementProf::with(['professeur', 'type', 'mode', 'session.formation'])->findOrFail($paiementId);
        $date = now()->format('d/m/Y');
        $heure = now()->format('H:i');
        $nom_prenom = $paiement->professeur->nomprenom;
        $telephone = $paiement->professeur->phone;
        $formation = $paiement->session->formation->nom;
        $date_debut = Carbon::parse($paiement->session->date_debut)->format('d/m/Y');
        $date_fin = Carbon::parse($paiement->session->date_fin)->format('d/m/Y');
        $mode_paiement = $paiement->mode->nom;
        $type_paiement = $paiement->type->type;
        $montant_paye = $paiement->montant_paye;
        $reste_a_payer = $paiement->montant_a_paye - $paiement->montant_paye;
        $date_paiement = Carbon::parse($paiement->date_paiement)->format('d/m/Y');
        $par = 'Nom de la personne qui a généré le reçu';  // remplacer par la variable appropriée
        $signature = 'Nom de la personne qui a signé';  // remplacer par la variable appropriée

        return view('livewire.example-laravel.receipt-prof', compact('date', 'heure', 'nom_prenom', 'telephone', 'formation', 'date_debut', 'date_fin', 'mode_paiement', 'type_paiement', 'montant_paye', 'reste_a_payer', 'date_paiement', 'par', 'signature'));
    }
}
