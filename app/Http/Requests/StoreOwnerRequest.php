<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class StoreOwnerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
   public function authorize(): bool
    {
       $user = $this->user();

        // Se houver um usuário, registra os detalhes dele no log
        if ($user) {
            Log::info('Verificação de autorização para criar proprietário:', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'system_role' => $user->system_role,
                'resultado_check' => ($user->system_role === 'internal')
            ]);
        } else {
            // Se por algum motivo não houver usuário, registra um aviso
            Log::warning('Tentativa de criar proprietário sem usuário autenticado.');
        }

        // Mantém a regra original
        return $user && $user->system_role === 'internal';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            // O email deve ser único na tabela users
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'document_number' => 'nullable|string|max:20',
            // Endereço é opcional no cadastro inicial do proprietário
            'address_street' => 'nullable|string|max:255',
            'address_number' => 'nullable|string|max:50',
            'address_neighborhood' => 'nullable|string|max:255',
            'address_city' => 'nullable|string|max:255',
            'address_state' => 'nullable|string|size:2',
            'address_zipcode' => 'nullable|string|max:10',
        ];
    }
}
