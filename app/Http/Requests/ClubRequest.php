<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class ClubRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Adjust as per your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|min:5|max:255',
            'isActive' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required',
            'isActive.required' => 'The active field is required',
        ];
    }
/*
    public function after(): array
    {
        return [
            function (Validator $validator) {
               print_r($validator->errors());
            }
        ];
    }
    */

}
