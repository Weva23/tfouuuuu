<?php


namespace App\Http\Livewire\ExampleLaravel;

use Illuminate\Http\Request;
use App\Models\Etudiant;
use App\Models\Country;
use App\Models\Sessions;
use App\Models\Paiement;
use App\Models\ModePaiement;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EtudiantExport;
use App\Models\Professeur;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EtudiantController extends Component
{
    public function liste_etudiant()
    {
        $etudiants = Etudiant::with('sessions')->paginate(4);
        $countries = Country::all();
        return view('livewire.example-laravel.etudiant-management', compact('etudiants', 'countries'));
    }

    // public function getEtudiantDetails($etudiantId)
    // {
    //     try {
    //         $etudiant = Etudiant::findOrFail($etudiantId);
    //         $formations = $etudiant->sessions->map(function ($session) use ($etudiantId) {
    //             $paiement = Paiement::where('etudiant_id', $etudiantId)->where('session_id', $session->id)->first();
    //             return [
    //                 'nom' => $session->nom,
    //                 'prix_reel' => $paiement ? $paiement->prix_reel : 0,
    //                 'montant_paye' => $paiement ? $paiement->montant_paye : 0,
    //                 'reste_a_payer' => $paiement ? $paiement->prix_reel - $paiement->montant_paye : 0,
    //             ];
    //         });
    
    //         return response()->json([
    //             'etudiant' => $etudiant,
    //             'formations' => $formations,
    //         ]);
    //     } catch (ModelNotFoundException $e) {
    //         return response()->json(['error' => 'Étudiant non trouvé'], 404);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => 'Erreur lors de la récupération des détails: ' . $e->getMessage()], 500);
    //     }
    // }
    public function getEtudiantDetails($etudiantId)
{
    try {
        $etudiant = Etudiant::findOrFail($etudiantId);
        $formations = $etudiant->sessions->map(function ($session) use ($etudiantId) {
            $paiement = Paiement::where('etudiant_id', $etudiantId)->where('session_id', $session->id)->first();
            $statut = (now()->between($session->date_debut, $session->date_fin)) ? 'En cours' : 'Terminé';
            return [
                'nom' => $session->nom,
                'prix_reel' => $paiement ? $paiement->prix_reel : 0,
                'montant_paye' => $paiement ? $paiement->montant_paye : 0,
                'reste_a_payer' => $paiement ? $paiement->prix_reel - $paiement->montant_paye : 0,
                'statut' => $statut,
            ];
        });

        return response()->json([
            'etudiant' => $etudiant,
            'formations' => $formations,
        ]);
    } catch (ModelNotFoundException $e) {
        return response()->json(['error' => 'Étudiant non trouvé'], 404);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Erreur lors de la récupération des détails: ' . $e->getMessage()], 500);
    }
}


    public function checkNni(Request $request)
    {
        $nni = $request->input('nni');
        $id = $request->input('id');

        $exists = Etudiant::where('nni', $nni)
            ->when($id, function ($query, $id) {
                return $query->where('id', '!=', $id);
            })
            ->exists();

        return response()->json(['exists' => $exists]);
    }


    public function checkEmail(Request $request)
    {
        $email = $request->input('email');
        $id = $request->input('id');

        $exists = Etudiant::where('email', $email)
            ->when($id, function ($query, $id) {
                return $query->where('id', '!=', $id);
            })
            ->exists();

        return response()->json(['exists' => $exists]);
    }
    public function checkPhone(Request $request)
    {
        $query = Etudiant::where('phone', $request->phone);
        if ($request->has('etudiant_id')) {
            $query->where('id', '!=', $request->etudiant_id);
        }
        $exists = $query->exists();
        return response()->json(['exists' => $exists]);
    }

    public function checkWtsp(Request $request)
    {
        $query = Etudiant::where('wtsp', $request->wtsp);
        if ($request->has('etudiant_id')) {
            $query->where('id', '!=', $request->etudiant_id);
        }
        $exists = $query->exists();
        return response()->json(['exists' => $exists]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nni' => 'required|digits:10|string|gt:0|unique:etudiants,nni',
            'nomprenom' => 'required|string',
            'diplome' => 'nullable|string',
            'genre' => 'required|string',
            'lieunaissance' => 'nullable|string',
            'adress' => 'nullable|string',
            'datenaissance' => 'nullable|date',
            'dateninscrip' => 'required|date',
            'email' => 'nullable|email|unique:etudiants,email',
            'phone' => 'required|digits:8|integer|gt:0',
            'wtsp' => 'nullable|integer',
            'country_id' => 'required|exists:countries,id',
        ]);

        try {
            $imageName = $request->hasFile('image') ? time() . '.' . $request->image->extension() : null;

            if ($imageName) {
                $request->image->move(public_path('images'), $imageName);
            }

            $etudiant = Etudiant::create([
                'image' => $imageName,
                'nni' => $request->nni,
                'nomprenom' => $request->nomprenom,
                'diplome' => $request->diplome,
                'genre' => $request->genre,
                'lieunaissance' => $request->lieunaissance,
                'adress' => $request->adress,
                'datenaissance' => $request->datenaissance,
                'dateninscrip' => $request->dateninscrip,
                'email' => $request->email,
                'phone' => $request->phone,
                'wtsp' => $request->wtsp,
                'country_id' => $request->country_id,
            ]);

            return response()->json(['success' => 'Étudiant créé avec succès', 'etudiant' => $etudiant]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'nni' => 'required|digits:10|string|gt:0|unique:etudiants,nni,' . $id,
            'nomprenom' => 'required|string',
            'diplome' => 'nullable|string',
            'genre' => 'required|string',
            'lieunaissance' => 'nullable|string',
            'adress' => 'nullable|string',
            'datenaissance' => 'nullable|date',
            'email' => 'nullable|email|unique:etudiants,email,' . $id,
            'phone' => 'required|digits:8|integer|gt:0',
            'wtsp' => 'nullable|integer',
            'country_id' => 'required|exists:countries,id',
        ]);

        try {
            $etudiant = Etudiant::findOrFail($id);

            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->image->getClientOriginalExtension();
                $request->image->move(public_path('images'), $imageName);
                $validated['image'] = $imageName;
            }

            $etudiant->update($validated);

            return response()->json(['success' => 'Étudiant modifié avec succès', 'etudiant' => $etudiant->load('country')]);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function deleteEtudiant($id)
    {
        $etudiant = Etudiant::find($id);
    
        if (!$etudiant) {
            return response()->json(['status' => 404, 'message' => 'Étudiant non trouvé.']);
        }
    
        $sessionsCount = $etudiant->sessions()->count();
    
        if ($sessionsCount > 0) {
            return response()->json([
                'status' => 400,
                'message' => 'Cet étudiant est associé à une ou plusieurs formations et ne peut pas être supprimé.'
            ]);
        }
    
        // Si aucune relation n'existe, confirmation de suppression
        return response()->json([
            'status' => 200,
            'message' => 'Voulez-vous vraiment supprimer cet étudiant?',
            'confirm_deletion' => true
        ]);
    }
    
    public function confirmDeleteEtudiant($id)
    {
        $etudiant = Etudiant::find($id);
    
        if (!$etudiant) {
            return response()->json(['status' => 404, 'message' => 'Étudiant non trouvé.']);
        }
    
        $etudiant->delete();
        return response()->json(['status' => 200, 'message' => 'Étudiant supprimé avec succès.']);
    }

    public function search(Request $request)
    {
        if ($request->ajax()) {
            $search = $request->search;
            $etudiants = Etudiant::where(function ($query) use ($search) {
                $query->where('id', 'like', "%$search%")
                    ->orWhere('nni', 'like', "%$search%")
                    ->orWhere('nomprenom', 'like', "%$search%")
                    ->orWhere('diplome', 'like', "%$search%")
                    ->orWhere('genre', 'like', "%$search%")
                    ->orWhere('lieunaissance', 'like', "%$search%")
                    ->orWhere('adress', 'like', "%$search%")
                    ->orWhere('datenaissance', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%")
                    ->orWhere('phone', 'like', "%$search%")
                    ->orWhere('wtsp', 'like', "%$search%");
            })->paginate(4);

            $view = view('livewire.example-laravel.etudiants-list', compact('etudiants'))->render();
            return response()->json(['html' => $view]);
        }
    }

    public function export()
    {
        return Excel::download(new EtudiantExport, 'Etudiants.xlsx');
    }

    public function render()
    {
        $etudiants = Etudiant::paginate(4);
        $countries = Country::all();
        return view('livewire.example-laravel.etudiant-management', compact('etudiants', 'countries'));
    }
}
