<?php
namespace App\Http\Livewire\ExampleLaravel;

use PDF;
use Illuminate\Http\Request;
use Livewire\Component;
use App\Models\Formations;
use App\Models\Sessions;
use App\Models\Professeur;
use App\Models\Etudiant;
use App\Models\Paiement;
use App\Models\PaiementProf;
use App\Models\ModePaiement;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SessionsExport;
use App\Models\Typeymntprofs;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Contracts\Session\Session;

class SessionsController extends Component
{
    public function list_session()
    {
        $sessions = Sessions::with('etudiants', 'professeurs', 'formation')->paginate(4);
        $formations = Formations::all();
        $modes_paiement = ModePaiement::all();
        $typeymntprofs = Typeymntprofs::all();
        $paiements = Paiement::with('etudiant', 'session.formation', 'mode')->get();
        $paiementsProf = PaiementProf::with('professeur', 'session.formation', 'mode')->get();
    
        return view('livewire.example-laravel.sessions-management', compact('sessions', 'formations', 'modes_paiement', 'typeymntprofs', 'paiements', 'paiementsProf'));
    }
    


    public function getProfDetails($sessionId, $profId)
    {
        try {
            $professeur = Professeur::with(['paiementprofs' => function ($query) use ($sessionId) {
                $query->where('session_id', $sessionId);
            }])->findOrFail($profId);

            $session = Sessions::findOrFail($sessionId);

            $montantPaye = $professeur->paiementprofs->where('session_id', $sessionId)->sum('montant_paye');
            $montantAPaye = $professeur->paiementprofs->where('session_id', $sessionId)->first()->montant_a_paye ?? 0;
            $montant = $professeur->paiementprofs->where('session_id', $sessionId)->first()->montant ?? 0;
            $typePaiement = $professeur->paiementprofs->where('session_id', $sessionId)->first()->typeymntprofs_id ?? 'N/A';
            $resteAPayer = $montantAPaye - $montantPaye;

            return response()->json([
                'success' => true,
                'montant' => $montant,
                'montant_a_paye' => $montantAPaye,
                'type_paiement' => $typePaiement,
                'reste_a_payer' => $resteAPayer
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Professeur ou session non trouvé.'], 404);
        } catch (\Exception $e) {
            Log::error('Error fetching professor details: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la récupération des détails du professeur.'], 500);
        }
    }
    public function getFormationDetails($id)
    {
        $formation = Formations::find($id);
        return response()->json(['formation' => $formation]);
    }


    public function addProfPaiement(Request $request, $sessionId)
    {
        Log::info('Received data:', $request->all());

        $request->validate([
            'prof_id' => 'required|exists:professeurs,id',
            'montant_paye' => 'required|numeric',
            'mode_paiement' => 'required|exists:modes_paiement,id',
            'date_paiement' => 'required|date',
            'typeymntprofs_id' => 'required|exists:typeymntprofs,id',
            'montant' => 'required|numeric',
            'montant_a_paye' => 'required|numeric'
        ]);

        try {
            $professeur = Professeur::findOrFail($request->prof_id);
            $session = Sessions::findOrFail($sessionId);

            // Vérifier si le professeur est déjà dans la session
            $existingProf = $session->professeurs()->where('prof_id', $request->prof_id)->first();
            if (!$existingProf) {
                return response()->json(['error' => 'Professeur non trouvé dans cette session.'], 404);
            }

            // Ajouter l'enregistrement de paiement
            $paiementprof = new PaiementProf([
                'prof_id' => $request->prof_id,
                'session_id' => $sessionId,
                'montant' => $request->montant,
                'montant_a_paye' => $request->montant_a_paye,
                'montant_paye' => $request->montant_paye,
                'mode_paiement_id' => $request->mode_paiement,
                'date_paiement' => $request->date_paiement,
                'typeymntprofs_id' => $request->typeymntprofs_id,
            ]);
            $paiementprof->save();

            return response()->json(['success' => 'Paiement ajouté avec succès']);
        } catch (ModelNotFoundException $e) {
            Log::error('Model not found: ' . $e->getMessage());
            return response()->json(['error' => 'Session ou Professeur non trouvé.'], 404);
        } catch (\Exception $e) {
            Log::error('Error adding payment: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de l\'ajout du paiement: ' . $e->getMessage()], 500);
        }
    }


    public function getProfSessionContents($sessionId)
    {
        $session = Sessions::with(['professeurs.paiementprofs.mode', 'formation'])->find($sessionId);

        if (!$session) {
            return response()->json(['error' => 'Session non trouvée'], 404);
        }

        $uniqueProfs = $session->professeurs->unique('id');
        $totalProfs = $uniqueProfs->count();

        // Calculer le total du montant à payer une seule fois par professeur dans chaque session
        $totalMontantAPaye = $uniqueProfs->sum(function($prof) use ($sessionId) {
            return $prof->paiementprofs->where('session_id', $sessionId)->unique('prof_id')->sum('montant_a_paye');
        });

        $totalMontantPaye = $uniqueProfs->sum(function($prof) use ($sessionId) {
            return $prof->paiementprofs->where('session_id', $sessionId)->sum('montant_paye');
        });

        $totalResteAPayer = $totalMontantAPaye - $totalMontantPaye;

        $professeurs = $uniqueProfs->map(function($professeur) use ($session) {
            $montantPaye = $professeur->paiementprofs->where('session_id', $session->id)->sum('montant_paye');
            $montant = $professeur->paiementprofs->where('session_id', $session->id)->first()->montant ?? 0;
            $montantAPaye = $professeur->paiementprofs->where('session_id', $session->id)->first()->montant_a_paye ?? 0;
            $resteAPayer = $montantAPaye - $montantPaye;

            return [
                'id' => $professeur->id,
                'nomprenom' => $professeur->nomprenom ?? 'N/A',
                'phone' => $professeur->phone ?? 'N/A',
                'wtsp' => $professeur->wtsp ?? 'N/A',
                'montant' => $montant,
                'montant_a_paye' => $montantAPaye,
                'montant_paye' => $montantPaye,
                'reste_a_payer' => $resteAPayer,
                'mode_paiement' => $professeur->paiementprofs->where('session_id', $session->id)->first()->mode->nom ?? 'N/A',
                'date_paiement' => $professeur->paiementprofs->where('session_id', $session->id)->first()->date_paiement ?? 'N/A',
            ];
        });

        return response()->json([
            'professeurs' => $professeurs,
            'prof_formation_nom' => $session->formation->nom,
            'prof_session_nom' => $session->nom,
            'total_profs' => $totalProfs,
            'prof_total_montant_a_paye' => $totalMontantAPaye,
            'prof_total_montant_paye' => $totalMontantPaye,
            'prof_total_reste_a_payer' => $totalResteAPayer,
        ]);
    }


    public function getSessionDates($id)
    {
        $session = Sessions::find($id);
    
        if ($session) {
            return response()->json([
                'start_date' => $session->date_debut,
                'end_date' => $session->date_fin,
            ]);
        } else {
            return response()->json(['error' => 'Session not found'], 404);
        }
    }

    public function searchProfByPhone(Request $request)
    {
        $phone = $request->phone;
        $professeur = Professeur::where('phone', $phone)->first();

        if ($professeur) {
            return response()->json(['professeur' => $professeur]);
        } else {
            return response()->json(['error' => 'Professeur non trouvé'], 404);
        }
    }

    
    public function addStudentToSession(Request $request, $sessionId)
    {
        $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id',
            'montant_paye' => 'required|numeric',
            'mode_paiement' => 'required|exists:modes_paiement,id',
            'date_paiement' => 'required|date',
            'prix_reel' => 'required|numeric',
            'note_test' => 'required|numeric'

        ]);

        try {
            $session = Sessions::findOrFail($sessionId);
            $etudiantId = $request->etudiant_id;

            // Attach the student to the session with the payment date
            $session->etudiants()->attach($etudiantId, [
                'date_paiement' => $request->date_paiement,
            ]);

            // Create a new Paiement record
            $paiement = new Paiement([
                'etudiant_id' => $etudiantId,
                'session_id' => $sessionId,
                'prix_reel' => $request->prix_reel,
                'note_test' => $request->note_test,
                'montant_paye' => $request->montant_paye,
                'mode_paiement_id' => $request->mode_paiement,
                'date_paiement' => $request->date_paiement,
            ]);
            $paiement->save();

            return response()->json(['success' => 'Étudiant et paiement ajoutés avec succès']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Formation not found.'], 404);
        } catch (\Exception $e) {
            Log::error('Error adding student to Formation: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de l\'ajout de l\'étudiant et du paiement: ' . $e->getMessage()], 500);
        }
    }

    public function checkProfInSession(Request $request, $sessionId)
    {
        $profId = $request->prof_id;
        $session = Sessions::with('professeurs')->findOrFail($sessionId);

        $isInSession = $session->professeurs->contains($profId);

        return response()->json(['isInSession' => $isInSession]);
    }

    public function getTotalStudentPayments($sessionId)
    {
        $session = Sessions::findOrFail($sessionId);
        $total = $session->etudiants->unique('id')->sum(function($etudiant) use ($sessionId) {
            return $etudiant->paiements->where('session_id', $sessionId)->sum('prix_reel');
        });
        return response()->json(['total' => $total]);
    }

    public function deleteProfFromSession($sessionId, $profId)
    {
        try {
            $session = Sessions::findOrFail($sessionId);
            $session->professeurs()->detach($profId);

            PaiementProf::where('session_id', $sessionId)->where('prof_id', $profId)->delete();

            return response()->json(['success' => 'Professeur retiré de la session avec succès']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la suppression du professeur: ' . $e->getMessage()], 500);
        }
    }

    public function addEtudiantToSession(Request $request, $sessionId, $etudiantId)
    {
        $request->validate([
            'montant_paye' => 'required|numeric',
            'mode_paiement' => 'required|exists:mode_paiements,id',
            'date_paiement' => 'required|date',
            'prix_reel' => 'required|numeric',
            'note_test' => 'required|numeric'

        ]);

        try {
            $session = Sessions::findOrFail($sessionId);
            $etudiant = Etudiant::findOrFail($etudiantId);

            // Check if the student is already in the session
            if ($session->etudiants()->where('etudiant_id', $etudiantId)->exists()) {
                return response()->json(['error' => 'L\'étudiant est déjà inscrit dans cette session.'], 400);
            }

            $session->etudiants()->attach($etudiantId, [
                'date_paiement' => $request->date_paiement,
            ]);

            $paiement = new Paiement([
                'etudiant_id' => $etudiantId,
                'session_id' => $sessionId,
                'prix_reel' => $request->prix_reel,
                'note_test' => $request->note_test,
                'montant_paye' => $request->montant_paye,
                'mode_paiement_id' => $request->mode_paiement,
                'date_paiement' => $request->date_paiement,
            ]);
            $paiement->save();

            return response()->json(['success' => 'Étudiant et paiement ajoutés avec succès']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Session ou étudiant non trouvé.'], 404);
        } catch (\Exception $e) {
            Log::error('Error adding student to session: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de l\'ajout de l\'étudiant et du paiement: ' . $e->getMessage()], 500);
        }
    }

    public function addProfToSession(Request $request, $sessionId)
    {
        $request->validate([
            'prof_id' => 'required|exists:professeurs,id',
            'montant_paye' => 'required|numeric',
            'mode_paiement' => 'required|exists:modes_paiement,id',
            'date_paiement' => 'required|date',
            'montant' => 'required|numeric',
            'montant_a_paye' => 'required|numeric',
            'typeymntprofs_id' => 'required|exists:typeymntprofs,id',
        ]);

        try {
            $session = Sessions::findOrFail($sessionId);
            $profId = $request->prof_id;

            // Check if the professor is already in the session
            if ($session->professeurs()->where('prof_id', $profId)->exists()) {
                return response()->json(['error' => 'Le professeur est déjà inscrit dans cette session.'], 400);
            }

            $session->professeurs()->attach($profId, [
                'date_paiement' => $request->date_paiement,
            ]);

            $paiementProf = new PaiementProf([
                'prof_id' => $profId,
                'session_id' => $sessionId,
                'montant' => $request->montant,
                'montant_a_paye' => $request->montant_a_paye,
                'montant_paye' => $request->montant_paye,
                'mode_paiement_id' => $request->mode_paiement,
                'date_paiement' => $request->date_paiement,
                'typeymntprofs_id' => $request->typeymntprofs_id,
            ]);
            $paiementProf->save();

            return response()->json(['success' => 'Professeur et paiement ajoutés avec succès']);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Session non trouvée.'], 404);
        } catch (\Exception $e) {
            Log::error('Error adding professor to session: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de l\'ajout du professeur et du paiement: ' . $e->getMessage()], 500);
        }
    }

    public function getStudentDetails($sessionId, $etudiantId)
    {
        
        try {
            $etudiant = Etudiant::with(['paiements' => function ($query) use ($sessionId) {
                $query->where('session_id', $sessionId);
            }])->findOrFail($etudiantId);
            $session = Sessions::findOrFail($sessionId);

            $montantPaye = $etudiant->paiements->sum('montant_paye');
            $prixReel = $session->formation->prix;
            $resteAPayer = $prixReel - $montantPaye;

            return response()->json([
                'success' => true,
                'prix_formation' => $session->formation->prix,
                'prix_reel' => $prixReel,
                'reste_a_payer' => $resteAPayer
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Étudiant ou Formation non trouvé.'], 404);
        } catch (\Exception $e) {
            Log::error('Error fetching student details: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la récupération des détails de l\'étudiant.'], 500);
        }
    }


    public function getSessionContents($sessionId)
    {
        $session = Sessions::with(['etudiants.paiements.mode', 'formation', 'professeurs'])->find($sessionId);

        if (!$session) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        $uniqueEtudiants = $session->etudiants->unique('id');
        $totalEtudiants = $uniqueEtudiants->count();

        // Calculer le total des prix réels une seule fois par étudiant dans chaque session
        $totalPrixReel = $uniqueEtudiants->sum(function($etudiant) use ($sessionId) {
            return $etudiant->paiements->where('session_id', $sessionId)->unique('etudiant_id')->sum('prix_reel');
        });

        $totalMontantPaye = $uniqueEtudiants->sum(function($etudiant) use ($sessionId) {
            return $etudiant->paiements->where('session_id', $sessionId)->sum('montant_paye');
        });

        $totalResteAPayer = $totalPrixReel - $totalMontantPaye;

        $uniqueProfesseurs = $session->professeurs->unique('id');
        $totalProfesseurs = $uniqueProfesseurs->count();

        $etudiants = $uniqueEtudiants->map(function($etudiant) use ($session) {
            $montantPaye = $etudiant->paiements->where('session_id', $session->id)->sum('montant_paye');
            $prixReel = $etudiant->paiements->where('session_id', $session->id)->first()->prix_reel ?? $session->formation->prix;
            $resteAPayer = $prixReel - $montantPaye;

            return [
                'id' => $etudiant->id,
                'nomprenom' => $etudiant->nomprenom,
                'phone' => $etudiant->phone,
                'wtsp' => $etudiant->wtsp,
                'prix_formation' => $session->formation->prix,
                'prix_reel' => $prixReel,
                'note_test' => $etudiant->paiements->where('session_id', $session->id)->first()->note_test ?? '',
                'montant_paye' => $montantPaye,
                'reste_a_payer' => $resteAPayer,
                'mode_paiement' => $etudiant->paiements->where('session_id', $session->id)->first()->mode->nom ?? '',
                'date_paiement' => $etudiant->paiements->where('session_id', $session->id)->first()->date_paiement ?? '',
            ];
        });

        return response()->json([
            'etudiants' => $etudiants,
            'formation_nom' => $session->formation->nom,
            'formation_price' => $session->formation->prix,
            'session_nom' => $session->nom,
            'total_etudiants' => $totalEtudiants,
            'total_prix_reel' => $totalPrixReel,
            'total_montant_paye' => $totalMontantPaye,
            'total_reste_a_payer' => $totalResteAPayer,
            'total_professeurs' => $totalProfesseurs,
        ]);
    }

    
    public function addPaiement(Request $request, $sessionId)
    {
        Log::info('Received data:', $request->all());
        // $request['mode_paiement']=1;
        // $request['date_paiement']='2024-07-19';
        $request->validate([
            'etudiant_id' => 'required|exists:etudiants,id',
            'montant_paye' => 'required|numeric',
            'mode_paiement' => 'required|exists:modes_paiement,id',
            'date_paiement' => 'required|date', // Ensure this field is validated
        ]);

        try {
            $etudiant = Etudiant::findOrFail($request->etudiant_id);
            $session = Sessions::findOrFail($sessionId);

            // Vérifier si le lien existe déjà
            $exists = $session->etudiants()->where('etudiant_id', $request->etudiant_id)->exists();

            if (!$exists) {
                $session->etudiants()->attach($request->etudiant_id, [
                    'date_paiement' => $request->date_paiement,
                ]);
            }

            $paiement = new Paiement([
                'etudiant_id' => $request->etudiant_id,
                'session_id' => $sessionId,
                'prix_reel' => $session->formation->prix,
                'montant_paye' => $request->montant_paye,
                'mode_paiement_id' => $request->mode_paiement,
                'date_paiement' => $request->date_paiement, // Ensure this field is set
            ]);
            $paiement->save();

            return response()->json(['success' => 'Paiement ajouté avec succès']);
        } catch (ModelNotFoundException $e) {
            Log::error('Model not found: ' . $e->getMessage());
            return response()->json(['error' => 'Formation ou Étudiant non trouvé.'], 404);
        } catch (\Exception $e) {
            Log::error('Error adding payment: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de l\'ajout du paiement: ' . $e->getMessage()], 500);
        }
    }

    public function deleteStudentFromSession($sessionId, $etudiantId)
    {
        try {
            $session = Sessions::findOrFail($sessionId);
            $session->etudiants()->detach($etudiantId);

            Paiement::where('session_id', $sessionId)->where('etudiant_id', $etudiantId)->delete();

            return response()->json(['success' => 'Étudiant retiré de la Formation avec succès']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la suppression de l\'étudiant: ' . $e->getMessage()], 500);
        }
    }
    
    public function generateReceipt($sessionId, $etudiantId)
    {
        try {
            $etudiant = Etudiant::findOrFail($etudiantId);
            $session = Sessions::findOrFail($sessionId);

            // Récupérer tous les paiements pour l'étudiant et la session donnés, triés par date de paiement
            $paiements = Paiement::where('etudiant_id', $etudiantId)
                ->where('session_id', $sessionId)
                ->orderBy('date_paiement', 'desc')
                ->get();

            if ($paiements->isEmpty()) {
                return response()->json(['error' => 'Aucun paiement trouvé pour cet étudiant et cette session'], 404);
            }

            // Utiliser le dernier paiement
            $dernierPaiement = $paiements->first();
            $montantPaye = $paiements->sum('montant_paye');
            $prixReel = $dernierPaiement->prix_reel;
            $resteAPayer = $prixReel - $montantPaye;
            $modePaiement = $dernierPaiement->mode->nom;

            // Convertir date_paiement en instance Carbon et définir le fuseau horaire sur Africa/Nouakchott
            $datePaiement = Carbon::parse($dernierPaiement->date_paiement)->setTimezone('Africa/Nouakchott');
            $datePaiementFormatted = $datePaiement->format('d/m/Y');
            $heurePaiement = $datePaiement->format('H:i');

            // Convertir les dates en objets DateTime
            $dateDebut = new \DateTime($session->date_debut);
            $dateFin = new \DateTime($session->date_fin);

            $data = [
                'date' => now()->setTimezone('Africa/Nouakchott')->format('d/m/Y'),
                'heure' => now()->setTimezone('Africa/Nouakchott')->format('H:i'),
                'nom_prenom' => $etudiant->nomprenom,
                'Telephone' => $etudiant->phone,
                'prix_reel' => $prixReel,
                'montant_paye' => $montantPaye,
                'reste_a_payer' => $resteAPayer,
                'Mode_peiment' => $modePaiement,
                'formation' => $session->nom,
                'date_debut' => $dateDebut->format('d/m/Y'),
                'date_fin' => $dateFin->format('d/m/Y'),
                'date_paiement' => $datePaiementFormatted,
                'heure_paiement' => $heurePaiement,
                'par' => Auth::user()->name,
                'signature' => 'Signature Autorisée',
            ];

            $pdf = PDF::loadView('livewire.example-laravel.receipt', $data);

            return $pdf->download('reçu.pdf');
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Étudiant ou Session non trouvé'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la génération du reçu: ' . $e->getMessage()], 500);
        }
    }

    public function generateProfReceipt($sessionId, $profId)
    {
        try {
            $professeur = Professeur::findOrFail($profId);
            $session = Sessions::findOrFail($sessionId);

            // Récupérer tous les paiements pour le professeur et la session donnés, triés par date de paiement
            $paiementsProf = PaiementProf::where('prof_id', $profId)
                ->where('session_id', $sessionId)
                ->orderBy('date_paiement', 'desc')
                ->get();

            if ($paiementsProf->isEmpty()) {
                return response()->json(['error' => 'Aucun paiement trouvé pour ce professeur et cette session'], 404);
            }

            // Utiliser le dernier paiement
            $dernierPaiementProf = $paiementsProf->first();
            $montantPaye = $paiementsProf->sum('montant_paye');
            $montantAPaye = $dernierPaiementProf->montant_a_paye;
            $resteAPayer = $montantAPaye - $montantPaye;
            $modePaiement = $dernierPaiementProf->mode ? $dernierPaiementProf->mode->nom : 'N/A';
            $typePaiement = $dernierPaiementProf->typeymntprofs ? $dernierPaiementProf->typeymntprofs->type : 'N/A';

            // Convertir date_paiement en instance Carbon et définir le fuseau horaire sur Africa/Nouakchott
            $datePaiement = Carbon::parse($dernierPaiementProf->date_paiement)->setTimezone('Africa/Nouakchott');
            $datePaiementFormatted = $datePaiement->format('d/m/Y');
            $heurePaiement = $datePaiement->format('H:i');

            // Convertir les dates en objets DateTime
            $dateDebut = new \DateTime($session->date_debut);
            $dateFin = new \DateTime($session->date_fin);

            $data = [
                'date' => now()->setTimezone('Africa/Nouakchott')->format('d/m/Y'),
                'heure' => now()->setTimezone('Africa/Nouakchott')->format('H:i'),
                'nom_prenom' => $professeur->nomprenom,
                'telephone' => $professeur->phone,
                'formation' => $session->nom,
                'date_debut' => $dateDebut->format('d/m/Y'),
                'date_fin' => $dateFin->format('d/m/Y'),
                'mode_paiement' => $modePaiement,
                'type_paiement' => $typePaiement,
                'montant_paye' => $montantPaye,
                'reste_a_payer' => $resteAPayer,
                'date_paiement' => $datePaiementFormatted,
                'heure_paiement' => $heurePaiement,
                'par' => Auth::user()->name,
                'signature' => 'Signature Autorisée',
            ];

            $pdf = PDF::loadView('livewire.example-laravel.prof_receipt', $data);

            return $pdf->download('reçu_professeur.pdf');
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Professeur ou Session non trouvé'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la génération du reçu: ' . $e->getMessage()], 500);
        }
    }

    
    public function searchStudentByPhone(Request $request)
    {
        $phone = $request->phone;
        $etudiant = Etudiant::where('phone', $phone)->first();
        return response()->json(['etudiant' => $etudiant]);
    }

    public function checkStudentInSession(Request $request, $sessionId)
    {
        $etudiantId = $request->etudiant_id;
        $session = Sessions::with('etudiants')->findOrFail($sessionId);

        $isInSession = $session->etudiants->contains($etudiantId);

        return response()->json(['isInSession' => $isInSession]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string',
            'date_debut' => 'required|date',
            'date_fin' => 'required|date',
            'formation_id' => 'required|exists:formations,id',
        ]);
    
        // Vérifiez si le nom existe déjà
        if (Sessions::where('nom', $request->nom)->exists()) {
            return response()->json(['error' => 'Le nom de Formation existe déjà.'], 409);
        }
    
        try {
            $session = Sessions::create($request->all());
            return response()->json(['success' => 'Formation créée avec succès', 'session' => $session]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $session = Sessions::with('formation')->findOrFail($id);
        return response()->json(['session' => $session]);
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date',
            'nom' => 'required|string',
            'formation_id' => 'required|exists:formations,id',
        ]);
    
        try {
            $session = Sessions::findOrFail($id);
            $session->update($request->all());
            return response()->json(['success' => 'Session modifiée avec succès', 'session' => $session]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
    

    public function destroy($id)
    {
        try {
            $session = Sessions::with('etudiants', 'professeurs')->findOrFail($id);
            if ($session->etudiants->isNotEmpty() || $session->professeurs->isNotEmpty()) {
                return response()->json(['status' => 400, 'message' => 'La Formation ne peut pas être supprimée car elle contient des étudiants ou des professeurs.']);
            }
    
            $session->delete();
            return response()->json(['success' => 'Formation supprimée avec succès']);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    // public function search_listetud(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $search = $request->search;
    //         $sessions = Sessions::with(['etudiants','paiements', ])
    //             ->where('montant_paye', 'like', "%$search%")
    //             ->orWhereHas('etudiants', function ($query) use ($search) {
    //                 $query->where('nomprenom', 'like', "%$search%")
    //                       ->orWhere('phone', 'like', "%$search%")
    //                       ->orWhere('wtsp', 'like', "%$search%");
    //             })
    //             ->orWhereHas('paiements', function ($query) use ($search) {
    //                 $query->where('prix_reel', 'like', "%$search%");
    //                 $query->where('montant_paye', 'like', "%$search%");
    //                 $query->where('note_test', 'like', "%$search%");
    //                 $query->where('date_paiement', 'like', "%$search%");

    //             })
    //             ->orWhereHas('mode', function ($query) use ($search) {
    //                 $query->where('nom', 'like', "%$search%");
    //             })
    //             ->paginate(10);
    
    //         $view = view('livewire.example-laravel.sessions-management', compact('sessions'))->render();
    //         return response()->json(['html' => $view]);
    //     }
    // }

    public function search_listetud(Request $request)
{
    if ($request->ajax()) {
        $search = $request->input('search');
        
        $sessions = Sessions::with(['etudiants', 'paiements'])
            ->where('montant_paye', 'like', "%$search%")
            ->orWhereHas('etudiants', function ($query) use ($search) {
                $query->where('nomprenom', 'like', "%$search%")
                      ->orWhere('phone', 'like', "%$search%")
                      ->orWhere('wtsp', 'like', "%$search%");
            })
            ->orWhereHas('paiements', function ($query) use ($search) {
                $query->where('prix_reel', 'like', "%$search%")
                      ->orWhere('montant_paye', 'like', "%$search%")
                      ->orWhere('note_test', 'like', "%$search%")
                      ->orWhere('date_paiement', 'like', "%$search%");
            })
            ->orWhereHas('mode', function ($query) use ($search) {
                $query->where('nom', 'like', "%$search%");
            })
            ->paginate(10);
        
        $view = view('livewire.example-laravel.sessions-management', compact('sessions'))->render();
        return response()->json(['html' => $view]);
    }
}

    public function search6(Request $request)
    {
        if ($request->ajax()) {
            $search6 = $request->search6;
            $sessions = Sessions::where('date_debut', 'like', "%$search6%")
                ->orWhere('date_fin', 'like', "%$search6%")
                ->orWhere('nom', 'like', "%$search6%")
                ->paginate(4);

            $view = view('livewire.example-laravel.sessions-list', compact('sessions'))->render();
            return response()->json(['html' => $view]);
        }
    }

    public function render()
    {
        return $this->list_session();
    }

    public function exportSessions()
    {
        return Excel::download(new SessionsExport(), 'sessions.xlsx');
    }
}