<?php

namespace App\Http\Livewire\ExampleLaravel;

use Illuminate\Http\Request;
use Livewire\Component;
use App\Models\Programmes;
use App\Models\Sessions;
use App\Exports\ProgrammesExport;
use Maatwebsite\Excel\Facades\Excel;

class ProgrammeController extends Component
{
    public function liste_programme()
    {
        $programmes = Programmes::orderBy('nom')->paginate(4);
        return view('livewire.example-laravel.programme-management', compact('programmes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
        ]);
    
        // Vérifiez si le code existe déjà
        if (Programmes::where('code', $request->code)->exists()) {
            return response()->json(['error' => 'Le code de Programme existe déjà.'], 409);
        }
    
        $programme = new Programmes([
            'code' => $request->code,
            'nom' => $request->nom,
        ]);
    
        if ($programme->save()) {
            return response()->json(['success' => 'Programme ajoutée avec succès.']);
        } else {
            return response()->json(['error' => 'Erreur lors de l\'ajout de la programme.'], 400);
        }
    }
    
        
    

    public function update(Request $request, $id)
    {
        $programme = Programmes::find($id);

        if ($programme) {
            $request->validate([
                'code' => 'required|string|max:255',
                'nom' => 'required|string|max:255',
            ]);

            $programme->update($request->all());

            return response()->json(['status' => 200, 'message' => 'Programme modifiée avec succès!']);
        } else {
            return response()->json(['status' => 404, 'message' => 'Programme non trouvée.']);
        }
    }

    // public function delete_programme($id)
    // {
    //     $programme = Programmes::find($id);

    //     if ($programme) {
    //         $sessions = Sessions::where('programme_id', $id)->get();

    //         if ($contenus->isNotEmpty() || $sessions->isNotEmpty()) {
    //             return response()->json([
    //                 'status' => 400,
    //                 'message' => 'Cette programme a des contenus ou des sessions associés et ne peut pas être supprimée.',
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'status' => 200,
    //                 'message' => 'Voulez-vous vraiment supprimer cette programme?',
    //                 'confirm_deletion' => true
    //             ]);
    //         }
    //     } else {
    //         return response()->json(['status' => 404, 'message' => 'programme non trouvée.']);
    //     }
    // }

    public function confirm_delete_programme($id)
    {
        $programme = Programmes::find($id);

        if ($programme) {
            Sessions::where('programme_id', $id)->delete();
            $programme->delete();

            return response()->json(['status' => 200, 'message' => 'programme et ses contenus supprimés avec succès.']);
        } else {
            return response()->json(['status' => 404, 'message' => 'programme non trouvée.']);
        }
    }


   
    public function export()
    {
        return Excel::download(new ProgrammesExport, 'programmes.xlsx');
    }

    public function render()
    {
        return $this->liste_programme();
    }


    public function search_programme(Request $request)
    {
        if ($request->ajax()) {
            $search1 = $request->search1;
            $programmes = Programmes::where(function($query) use ($search1) {
                $query->where('id', 'like', "%$search1%")
                    ->orWhere('code', 'like', "%$search1%")
                    ->orWhere('nom', 'like', "%$search1%");
            })->paginate(4);

            $view = view('livewire.example-laravel.programmes-list', compact('programmes'))->render();
            return response()->json(['html' => $view]);
        }
    }



}