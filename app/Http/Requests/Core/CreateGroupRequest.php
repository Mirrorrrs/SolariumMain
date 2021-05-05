<?php

namespace App\Http\Requests\Core;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CreateGroupRequest extends FormRequest
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
            "name" => ["required", "regex:/^[а-яё]+$/ium","unique:groups,name"],
            "human_name" => ["required", "regex:/^[а-яё]+$/ium"],
            "description" => "required|max:255",
            "permissions" => "required|json"
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        return new HttpResponseException(response()->json($validator->errors()));
    }


}
