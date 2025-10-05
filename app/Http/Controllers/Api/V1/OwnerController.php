<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOwnerRequest;
use App\Http\Requests\UpdateOwnerRequest; // <-- Vamos criar este
use App\Http\Resources\OwnerResource;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response; // <-- Adicionado para o destroy

class OwnerController extends Controller
{
    /**
     * Lista os usuários que são proprietários.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        // Pega o usuário autenticado que está fazendo a requisição
        $user = auth()->user();

        $owners = User::query()
            // ESTA É A LINHA DA CORREÇÃO:
            // Filtra para buscar apenas usuários do MESMO tenant do usuário logado.
            ->where('tenant_id', $user->tenant_id)
            ->where('system_role', 'owner')
            // O resto da consulta continua igual...
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return OwnerResource::collection($owners);
    }

    /**
     * Cria um novo usuário proprietário.
     */
    public function store(StoreOwnerRequest $request)
    {
        $this->authorize('create', User::class);

        $validatedData = $request->validated();

        $owner = User::create(array_merge($validatedData, [
            'tenant_id' => auth()->user()->tenant_id,
            'password' => bcrypt(Str::random(16)),
            'system_role' => 'owner',
        ]));

        return new OwnerResource($owner);
    }

    /**
     * Exibe um proprietário específico.
     */
    public function show(User $owner)
    {
        // Garante que estamos tratando apenas de proprietários
        if ($owner->system_role !== 'owner') {
            abort(404);
        }

        $this->authorize('view', $owner);
        return new OwnerResource($owner);
    }

    /**
     * Atualiza um proprietário específico.
     */
    public function update(UpdateOwnerRequest $request, User $owner)
    {
        $this->authorize('update', $owner);

        $validatedData = $request->validated();
        $owner->update($validatedData);

        return new OwnerResource($owner);
    }

    /**
     * Remove um proprietário específico.
     */
    public function destroy(User $owner)
    {
        if ($owner->system_role !== 'owner') {
            abort(404);
        }

        $this->authorize('delete', $owner);
        $owner->delete();

        return response()->noContent(); // Retorna uma resposta 204 No Content
    }
}
