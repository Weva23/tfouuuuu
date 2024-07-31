<?php
    
namespace App\Http\Controllers;
   
use Illuminate\Http\Request;
use App\Models\Etudiant;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
    
class SearchetudController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        return view('livewire.example-laravel.etudiant-management');
    }
      
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function autocomplete(Request $request): JsonResponse
    {
        $data = [];
    
        if($request->filled('q')){
            $data = Etudiant::select(
                'nni',
            'nomprenom',
            'diplome',
            'genre',
            'lieunaissance',
            'adress',
            'datenaissance',
            'email',
            'phone',
            'wtsp',)
                        ->where('nni', 'LIKE', '%'. $request->get('q'). '%')
                        ->orWhere('nomprenom', 'LIKE', '%'. $request->get('q'). '%')
                        ->orWhere('diplome', 'LIKE', '%'. $request->get('q'). '%')
                        ->orWhere('genre', 'LIKE', '%'. $request->get('q'). '%')
                        ->orWhere('lieunaissance', 'LIKE', '%'. $request->get('q'). '%')
                        ->orWhere('datenaissance', 'LIKE', '%'. $request->get('q'). '%')
                        ->orWhere('email', 'LIKE', '%'. $request->get('q'). '%')
                        ->orWhere('phone', 'LIKE', '%'. $request->get('q'). '%')
                        ->orWhere('wtsp', 'LIKE', '%'. $request->get('q'). '%')
                        // ->orWhere('country_id', 'LIKE', '%'. $request->get('q'). '%')

                        ->get();
        }
     
        return response()->json($data);
    }
}