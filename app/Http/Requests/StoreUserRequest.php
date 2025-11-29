<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
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
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'telefono' => 'nullable|string|max:20',
            'rol' => 'required|in:admin,repartidor',
            'licencia' => 'nullable|string|max:50',
            'activo' => 'boolean',
        ];
    }

    public function messages()
    {
        return[
            'email.unique' => 'Ya tienes un contacto con este email',
        ];
    }
}
