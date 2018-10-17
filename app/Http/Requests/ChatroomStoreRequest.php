<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatroomStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::check();
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
            'users' => 'required|array'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'name.min' => 'Name should be longer than 3 characters',
            'name.max' => 'Name should be shorter than 255 characters',

            'users.required' => 'You are required to add users to the new chatroom',
            'users.array' => 'Users field got corrupted please try again'
        ];
    }
}
