<?php

namespace App\Http\Requests\Core;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AttachGroupRequest extends FormRequest
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
            "users"=>["required_without:user","json"],
            "user"=>["required_without:users"],
            "groups"=>["required_without:group","json"],
            "group"=>["required_without:groups"],

        ];
    }

    protected function failedValidation(Validator $validator)
    {
        return new HttpResponseException(response()->json($validator->errors()));
    }
}
