<?php
namespace App\Http\Livewire\ExampleLaravel;

use Illuminate\Http\Request;
use Livewire\Component;
use App\Models\Paiement;
use App\Models\Etudiant;
use App\Models\Sessions;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaiementsExport;

class PaiementController extends Component
{
    public function list_paiement()
    {
        $paiements = Paiement::with(['etudiant', 'session.formation', 'mode'])
            ->join('etudiants', 'paiements.etudiant_id', '=', 'etudiants.id')
            ->join('sessions', 'paiements.session_id', '=', 'sessions.id')
            ->orderBy('sessions.nom', 'asc')
            ->orderBy('etudiants.nomprenom', 'asc')
            ->select('paiements.*')
            ->paginate(8);

        foreach ($paiements as $paiement) {
            $montantPayeTotal = Paiement::where('etudiant_id', $paiement->etudiant_id)
                ->where('session_id', $paiement->session_id)
                ->sum('montant_paye');
            $paiement->reste_a_payer = $paiement->prix_reel - $montantPayeTotal;
        }

        return view('livewire.example-laravel.paiement-management', compact('paiements'));
    }

    public function render()
    {
        return $this->list_paiement();
    }

    public function exportPaiements()
    {
        return Excel::download(new PaiementsExport, 'paiements.xlsx');
    }

    public function searchPayments(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->search;
            $paiements = Paiement::with(['etudiant', 'session', 'mode'])
                ->where('montant_paye', 'like', "%$search%")
                ->orWhereHas('etudiant', function ($query) use ($search) {
                    $query->where('nomprenom', 'like', "%$search%")
                          ->orWhere('phone', 'like', "%$search%")
                          ->orWhere('wtsp', 'like', "%$search%");
                })
                ->orWhereHas('session', function ($query) use ($search) {
                    $query->where('nom', 'like', "%$search%");
                })
                ->orWhereHas('mode', function ($query) use ($search) {
                    $query->where('nom', 'like', "%$search%");
                })
                ->paginate(10);

            $view = view('livewire.example-laravel.paiements-list', compact('paiements'))->render();
            return response()->json(['html' => $view]);
        }
    }

    public function generateReceipt($paiementId)
{
    $paiement = Paiement::with(['etudiant', 'session.formation', 'mode'])->findOrFail($paiementId);
    $date = now()->format('d/m/Y');
    $heure = now()->format('H:i');
    $nom_prenom = $paiement->etudiant->nomprenom;
    $Telephone = $paiement->etudiant->phone;
    $formation = $paiement->session->formation->nom;
    $date_debut = Carbon::parse($paiement->session->date_debut)->format('d/m/Y');
    $date_fin = Carbon::parse($paiement->session->date_fin)->format('d/m/Y');
    $Mode_peiment = $paiement->mode->nom;
    $montant_paye = $paiement->montant_paye;
    $reste_a_payer = $paiement->prix_reel - $paiement->montant_paye;
    $date_paiement = Carbon::parse($paiement->date_paiement)->format('d/m/Y');
    $par = 'Nom de la personne qui a généré le reçu';  // remplacer par la variable appropriée
    $signature = 'Nom de la personne qui a signé';  // remplacer par la variable appropriée

    return view('livewire.example-laravel.receipt', compact('date', 'heure', 'nom_prenom', 'Telephone', 'formation', 'date_debut', 'date_fin', 'Mode_peiment', 'montant_paye', 'reste_a_payer', 'date_paiement', 'par', 'signature'));
}
}
