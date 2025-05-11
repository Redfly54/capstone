<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
         return [
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6', 
            'phone' => 'required|string|max:13|min:10',
            'alamat' => 'required|string|max:255',
            'kelurahan' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:30',
            'kota' => 'required|string|max:30',
            'provinsi' => 'required|string|max:30',
            'animal_type' => 'required|string|max:30',
            'breed' => 'required|string|max:30',
            'animal_gender' => 'required|string|max:30',
            'age_group' => 'required|string|max:30',
            'color_count' => 'required|integer|max:30',
        ];
    }
}
