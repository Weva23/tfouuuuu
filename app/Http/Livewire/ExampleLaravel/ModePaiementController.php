<?php
  
namespace App\Http\Livewire\ExampleLaravel;

use Illuminate\Http\Request;
use Livewire\Component;
use App\Models\ModePaiement;
    
    class ModePaiementController extends Component
    {
        public function index()
        {
            $modes_paiement = ModePaiement::all();
            return view('mode', compact('modes_paiement'));
        }
    }