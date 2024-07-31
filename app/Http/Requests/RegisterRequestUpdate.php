<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequestUpdate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nomupdate' => ['required', 'string', 'max:255'],
            'prenomupdate' => ['required', 'string', 'max:255'],
            'emailupdate' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'telephoneupdate' => ['required', 'string', 'max:255'],
        ];
    }
}