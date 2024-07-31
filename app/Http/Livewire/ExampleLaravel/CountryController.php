<?php
  
  namespace App\Http\Livewire\ExampleLaravel;
  use Illuminate\Http\Request;
    use Livewire\Component;

    use App\Models\Country;
    
    class CountryController extends Component
    {
        /**
         * Write code on Method
         *
         * @return response()
         */
        public function index()
        {
            $countries = Country::all();
            return view('country',compact('countries'));
        }
    }