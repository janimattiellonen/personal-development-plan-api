<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
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
            'name' => 'required|string|min:4|max:255',
            'age' => 'nullable|numeric|min:5',
            'isActive' => 'nullable|boolean',
            'firstName' => 'required|string|min:2|max:64',
            'lastName' => 'required|string|min:2|max:64',
            'username' => 'required|string|email|min:5|max:255',
            'password' => 'required|string|min:5|max:255',
            'type' => 'required|string|min:2|max:10',
            'userRole' => 'required|string|min:2|max:10',
        ];
    }
}
