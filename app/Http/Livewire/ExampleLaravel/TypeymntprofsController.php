<?php
  
  namespace App\Http\Livewire\ExampleLaravel;
  use Illuminate\Http\Request;
    use Livewire\Component;

    use App\Models\Typeymntprofs;
    
    class TypeymntprofsController extends Component
    {
        /**
         * Write code on Method
         *
         * @return response()
         */
        public function index()
        {
            $countries = Typeymntprofs::all();
            return view('type',compact('typeymntprofs'));
        }
    }