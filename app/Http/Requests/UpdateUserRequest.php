<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password as PasswordRule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "nom" => ['required', 'max:30', "string"],
            "prenom" => ['required', 'max:60', "string"],
            "adresse" => ['required', 'max:30', "string"],
            "telephone" => ['required', "string"],
            "image" => "sometimes",
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . auth()->id()],
            'password' => [PasswordRule::default(), 'confirmed'],
        ];
    }

    public function failedValidation(validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'status_code' => 422,
            'errors' => $validator->errors()
        ]));
    }
}
