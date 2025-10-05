<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterTenantRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Tenant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Credenciais inválidas'], 401);
        }

        $user = User::where('email', $request->email)->first();

        // Opcional: apaga tokens antigos para manter apenas um login ativo
        $user->tokens()->delete();

        // Cria um novo token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user()->load('tenant'));
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logout realizado com sucesso']);
    }

    public function register(RegisterTenantRequest $request)
    {
        // Usamos uma transação para garantir que ambas as criações (tenant e user)
        // aconteçam com sucesso, ou nenhuma delas acontece.
        try {
            DB::beginTransaction();

            // 1. Criar a Imobiliária (Tenant)
            $tenant = Tenant::create([
                'name' => $request->input('tenant_name'),
                'slug' => Str::slug($request->input('tenant_name')), // <-- ADICIONE ESTA LINHA
                'custom_domain' => null, // Podemos adicionar isso para garantir
                'address_city' => $request->input('tenant_city'),
                'address_state' => $request->input('tenant_state'),
                'address_zipcode' => $request->input('tenant_zipcode'),
            ]);

            // 2. Criar o Usuário Administrador
            $adminUser = User::create([
                'tenant_id' => $tenant->id,
                'name' => $request->input('user_name'),
                'email' => $request->input('user_email'),
                'password' => bcrypt($request->input('user_password')),
                'system_role' => 'internal',
            ]);

            // 3. Atribuir o papel de Administrador
            $adminRole = Role::where('name', 'Administrador')->firstOrFail();
            $adminUser->assignRole($adminRole);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            // Retorna um erro genérico se algo falhar
            return response()->json(['message' => 'Falha no registro.', 'error' => $e->getMessage()], 500);
        }

        // 4. Gerar o token de acesso para login automático
        $token = $adminUser->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Imobiliária registrada com sucesso!',
            'access_token' => $token,
            'token_type' => 'Bearer',
            // Opcional: retornar alguns dados do usuário/imobiliária
            'user' => [
                'name' => $adminUser->name,
                'email' => $adminUser->email,
            ]
        ], 201); // Status 201 Created
    }
}
