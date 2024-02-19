<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password as PasswordRule;

class UpdateAdminSystemRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nom' => ['required', 'max:30', "alpha",'min:2'],
            'prenom' => ['required', 'regex:/^[a-zA-Z][a-zA-Z -]{2,100}$/'],
            'email' => ['nullable', 'regex:/^[A-Za-z]+[A-Za-z0-9\._%+-]+@+[A-Za-z][A-Za-z0-9\.-]+\.[A-Za-z]{2,}$/', 'max:255', 'unique:admin_systems,email'],
            'image' => ['sometimes','image'],
            'password' => ['sometimes',PasswordRule::min(8)->mixedCase()->numbers()->symbols(), 'confirmed'],
        ];
    }

    public function failedValidation(validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'errors' => $validator->errors()
        ],422));
    }
}
