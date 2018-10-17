<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateStudieroute extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->hasRole(['teacher', 'admin']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:100',
            'key' => 'required|unique:studieroutes|max:10',
            'description' => 'required',
            'due_date' => 'sometimes|nullable|date_format:Y-m-d',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Title is required',
            'name.max' => 'Title needs to be less than 100 characters',
            'key.required' => 'Key is required',
            'key.unique' => 'Key needs to be unique',
            'key.max' => 'Max key length is 10',
            'description.required' => 'description is required',
            'due_date.date_format' => 'Date format needs to be Year-Month-Day',

        ];
    }
}
