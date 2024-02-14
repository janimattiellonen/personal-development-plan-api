<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class TrainingsessionRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'isActive' => 'nullable|boolean',
            'startsAt' => 'required|date_format:Y-m-d H:i:s',
            'endsAt' => 'nullable|date_format:Y-m-d H:i:s',
            'personalPlanId' => 'required|exists:personal_plans,id',
        ];
    }
}
