<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfilePost extends FormRequest
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
        return[
            'first_name' => 'required|max:45',
            'last_name' => 'required|max:45',
            'birthdate' => 'required|date_format:Y-m-d',
            'email' => 'required|email',
            'phonenr' => 'required|max:15',
            'description' => 'required|max:200'
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
            'email.required' => 'Email is required',
            'email.email' => 'Email is invalid email format',
            'phonenr.required' => 'Phonenr is required',
            'description.required' => 'Bio is required'
        ];
    }
}

