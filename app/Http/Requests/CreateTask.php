<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateTask extends FormRequest
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
            'description' => 'required',
            'end_date' => 'sometimes|nullable|date_format:Y-m-d',
            'my_document' => 'sometimes|nullable|sometimes|mimes:jpeg,png,jpg,doc,docx,pdf,rar'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Title is required',
            'name.max' => 'Title needs to be less than 100 characters',
            'description.required' => 'description is required',
            'end_date.date_format' => 'Date format needs to be Year-Month-Day',
            'my_document.mimes' => 'Please upload a file with the following types: jpeg, png, jpg, doc, docx, pdf or rar'

        ];
    }
}
