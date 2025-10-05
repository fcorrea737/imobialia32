<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePropertyRequest; // <-- Importe a nova request
use App\Http\Resources\PropertyResource;
use App\Http\Requests\UpdatePropertyRequest;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Property::class);

        // 1. Buscamos as propriedades com Eager Loading para evitar o problema N+1
        $properties = Property::with(['owner:id,uuid,name', 'tenant:id,uuid,name'])->paginate(15);

        // 2. Retornamos uma coleção de recursos, que formatará a saída JSON
        return PropertyResource::collection($properties);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePropertyRequest $request)
   {
        $validatedData = $request->validated();

        $owner = \App\Models\User::where('uuid', $validatedData['owner_id'])->firstOrFail();

        $property = Property::create(array_merge($validatedData, [
        'owner_id' => $owner->id
    ]));

    return new PropertyResource($property);
    }

    /**
     * Display the specified resource.
     */
    public function show(Property $property)
    {

        $this->authorize('view', $property);
        return new PropertyResource($property->load('owner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePropertyRequest $request, Property $property)
    {
       // A autorização e a validação já foram feitas pela UpdatePropertyRequest!

        // Pega os dados validados da requisição
        $validatedData = $request->validated();

        // Atualiza o imóvel com os novos dados
        $property->update($validatedData);

       // DEPOIS:
    // Permite a ação se o usuário logado for um usuário interno ('admin', 'corretor', etc.)
    // E tiver a permissão para editar imóveis.
             return $this->user()->system_role === 'internal' &&
                 $this->user()->can('imoveis_editar');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Property $property)
    {
       // Verificação 1: O usuário tem PERMISSÃO para sequer TENTAR deletar?
    // (Ele é um funcionário com a permissão 'imoveis_deletar'?)
    $this->authorize('delete', $property);

    // Verificação 2: A REGRA DE NEGÓCIO permite que ESTE imóvel específico seja deletado?
    // (Ele tem algum contrato associado?)
    if ($property->contracts()->exists()) {
        // Se a regra de negócio impede a exclusão, retornamos um erro claro.
        // 409 Conflict é um bom status HTTP para "a ação não pode ser realizada no estado atual do recurso".
        return response()->json([
            'message' => 'Este imóvel não pode ser excluído pois possui um histórico de locações. Você pode inativá-lo.',
            'error_code' => 'DELETION_FORBIDDEN_HAS_HISTORY'
        ], 409);
    }

    // Se passou em ambas as verificações, então o soft delete é permitido.
    $property->delete();

    return response()->noContent();
    }

    /**
     * Lista os imóveis que pertencem ao proprietário autenticado.
     */
    public function myProperties(Request $request)
    {
        $user = auth()->user();

        // Garante que apenas usuários do tipo 'owner' podem acessar esta rota.
        if ($user->system_role !== 'owner') {
            return response()->json(['message' => 'This action is unauthorized.'], 403);
        }

        $properties = $user->ownedProperties() // Usa o relacionamento que já definimos!
                          ->with('owner') // Opcional: para manter a consistência da resposta
                          ->latest()
                          ->paginate(15);

        return PropertyResource::collection($properties);
    }
}
