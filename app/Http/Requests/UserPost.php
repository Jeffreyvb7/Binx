<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserPost extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
        //return Auth::user()->hasRole(['admin']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|max:45',
            'last_name' => 'required|max:45',
            'birthdate' => 'required|date_format:Y-m-d',
            'email' => 'required|max:100',
            'password'=>'required|min:8',
            'phonenr' => 'required|max:15',
            'role' => 'required|numeric'
        ];
    }

    public function messages()
    {
        return [
            'first_name.required' => 'First name is required',
            'first_name.max' => 'First name cannot be longer than 45 characters',
            'last_name.required' => 'Last name is required',
            'last_name.max' => 'Last name cannot be longer than 45 characters',
            'birthdate.required' => 'Birthdate is requird',
            'birthdate.date_format' => 'Birthdate needs to be Year-Month-Day',
            'email.required' => 'Email cannot be longer than 100 characters',
            'password.required' => 'Password is required',
            'password.min' => 'Password needs to be longer then 8 characters',
            'phonenr.required' => 'Phonenr cannot be longer than 15 characters',
            'role.required' => 'Please assign this user a role',
            'role.numeric' => 'Invalid role format',
        ];
    }
}
