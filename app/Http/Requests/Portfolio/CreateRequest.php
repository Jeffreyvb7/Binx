<?php

namespace App\Http\Requests\Portfolio;

use Illuminate\Foundation\Http\FormRequest;
use Auth;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check() && Auth::user()->hasPermissionTo('create portfolio') && $this->route('profile')->user->id === Auth::user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:3|max:255',
            'description' => 'sometimes|min:10|max:500'
        ];
    }

    /**
     * Custom validation messages
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'You are required to fill in a name',
            'name.min' => 'Please chose a name longer than 3 characters',
            'name.max' => 'Please chose a name shorter than 255 characters',

            'description.min' => 'Please chose a description longer than 10 characters',
            'description.max' => 'Please chose a description shorter than 500 characters'
        ];
    }
}
