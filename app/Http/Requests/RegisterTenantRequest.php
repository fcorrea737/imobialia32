<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterTenantRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Qualquer um pode tentar se registrar.
        return true;
    }

    public function rules(): array
    {
        return [
            // Dados da Imobiliária (Tenant)
            'tenant_name' => 'required|string|max:255',
            'tenant_city' => 'required|string|max:255',
            'tenant_state' => 'required|string|size:2',
            'tenant_zipcode' => 'required|string|max:10',

            // Dados do Usuário Administrador
            'user_name' => 'required|string|max:255',
            'user_email' => 'required|string|email|max:255|unique:users,email',
            'user_password' => 'required|string|min:8|confirmed', // 'confirmed' exige um campo 'user_password_confirmation'
        ];
    }
}
