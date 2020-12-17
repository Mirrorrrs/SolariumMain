<?php

namespace App\Http\Requests\Core;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;


class RegistrationRequest extends FormRequest
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
            "firstname" => ["required", "regex:/^[А-ЯЁ][а-яё]+$/ium"],
            "middlename" => ["required", "regex:/^[А-ЯЁ][а-яё]+$/ium"],
            "lastname" => ["required", "regex:/^[А-ЯЁ][а-яё]+$/ium"],
            "group" => ["required", "regex:/^([1-9А-Я]|[1-9А-Я][0-9А-Я]){1,}$/ium"],
            "group_number" => ["required", "regex:/^([1-9А-Я]|[1-9А-Я][0-9А-Я]){1,}$/ium"],
            "login" => ["regex:/^[A-Za-z]{5,}$/ium"],
            "organization_id"=>"required|exists:organizations,id",
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        return new HttpResponseException(response()->json($validator->errors()));
    }


}
