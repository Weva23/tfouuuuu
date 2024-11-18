<?php

namespace App\Http\Livewire\ExampleLaravel;

use Illuminate\Http\Request;
use Livewire\Component;
use App\Models\Formations;
use App\Models\Programmes;
use Illuminate\Support\Facades\Auth;
use App\Models\ContenusFormation;
use App\Models\Sessions;
use App\Exports\FormationsExport;
use Maatwebsite\Excel\Facades\Excel;

class FormationsController extends Component
{
    public function liste_formation()
    {
        $formations = Formations::with('programme')->orderBy('nom')->paginate(4);
        $programmes = Programmes::all(); // Charger tous les programmes
        return view('livewire.example-laravel.formations-management', compact('formations', 'programmes'));
    }

    public function store(Request $request)
{
    // Validate the incoming data
    $validated = $request->validate([
        'programme_id' => 'required|exists:programmes,id', // Ensure valid programme
        'code' => 'required|string|max:255|unique:formations,code', // Ensure unique code
        'nom' => 'required|string|max:255',
        'duree' => 'required|integer|min:1',
        'prix' => 'required|numeric|min:0',
    ]);

    try {
        // Create the formation
        $formation = Formations::create([
            'programme_id' => $validated['programme_id'],
            'code' => $validated['code'],
            'nom' => $validated['nom'],
            'duree' => $validated['duree'],
            'prix' => $validated['prix'],
            'created_by' => Auth::id(), // Attach the current user
        ]);

        return response()->json(['success' => 'Formation ajoutée avec succès.']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Erreur lors de l\'ajout de la formation.', 'details' => $e->getMessage()], 500);
    }
}

    
    

    public function update(Request $request, $id)
    {
        $formation = Formations::find($id);

        if ($formation) {
            $request->validate([
                'code' => 'required|string|max:255',
                'nom' => 'required|string|max:255',
                'duree' => 'required|integer',
                'prix' => 'required|integer',
                'programme_id' => 'required|exists:programmes,id', // Validation du programme
            ]);

            $formation->update($request->all());

            return response()->json(['status' => 200, 'message' => 'Formation modifiée avec succès!']);
        }

        return response()->json(['status' => 404, 'message' => 'Formation non trouvée.']);
    }

    public function delete_formation($id)
    {
        $formation = Formations::find($id);

        if ($formation) {
            $formation->delete();
            return response()->json(['status' => 200, 'message' => 'Formation supprimée avec succès.']);
        }

        return response()->json(['status' => 404, 'message' => 'Formation non trouvée.']);
    }

    public function export()
    {
        return Excel::download(new FormationsExport, 'formations.xlsx');
    }

    public function render()
    {
        return $this->liste_formation();
    }


    public function show($id)
    {
        $formation = Formations::with('contenusFormation')->find($id);

        if ($formation) {
            return response()->json(['formation' => $formation, 'contenus' => $formation->contenusFormation]);
        } else {
            return response()->json(['error' => 'Formation non trouvée'], 404);
        }
    }

    public function search1(Request $request)
    {
        if ($request->ajax()) {
            $search1 = $request->search1;
            $formations = Formations::where(function($query) use ($search1) {
                $query->where('id', 'like', "%$search1%")
                    ->orWhere('code', 'like', "%$search1%")
                    ->orWhere('nom', 'like', "%$search1%")
                    ->orWhere('duree', 'like', "%$search1%");
            })->paginate(4);

            $view = view('livewire.example-laravel.formations-list', compact('formations'))->render();
            return response()->json(['html' => $view]);
        }
    }

    public function getFormationContents($id)
    {
        $formation = Formations::with('contenusFormation')->find($id);

        if ($formation) {
            return response()->json(['contenus' => $formation->contenusFormation]);
        } else {
            return response()->json(['error' => 'Formation non trouvée'], 404);
        }
    }

    public function showContents($id)
    {
        $formation = Formations::with('contenusFormation')->find($id);
        if (!$formation) {
            return response()->json(['error' => 'Formation non trouvée'], 404);
        }

        return response()->json(['contenus' => $formation->contenusFormation]);
    }
    public function getContents($formationId)
    {
        $contenus = ContenusFormation::where('formation_id', $formationId)->get();
        return response()->json(['contenus' => $contenus]);
    }

}