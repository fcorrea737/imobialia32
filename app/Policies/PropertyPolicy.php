<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PropertyPolicy
{
    /**
     * Determina se um usuário pode ver a LISTA GERAL de imóveis.
     */
    public function viewAny(User $user): bool
    {
        return $user->system_role === 'internal';
    }

    /**
     * Determina se um usuário pode ver os DETALHES de um imóvel específico.
     */
    public function view(User $user, Property $property): bool
    {
        // Permite se o usuário for 'internal' OU se ele for o dono do imóvel.
        return $user->system_role === 'internal' || $user->id === $property->owner_id;
    }

    /**
     * Determina se um usuário pode CRIAR novos imóveis.
     */
    public function create(User $user): bool
    {
        // A REGRA CORRETA:
        // Apenas usuários internos com a permissão 'imoveis_criar' podem criar.
        return $user->system_role === 'internal' && $user->can('imoveis_criar');
    }

    /**
     * Determina se um usuário pode ATUALIZAR um imóvel.
     */
    public function update(User $user, Property $property): bool
    {
        // A REGRA CORRETA:
        // Apenas usuários internos com a permissão 'imoveis_editar' podem atualizar.
        return $user->system_role === 'internal' && $user->can('imoveis_editar');
    }

    /**
     * Determina se um usuário pode DELETAR um imóvel.
     */
    public function delete(User $user, Property $property): bool
    {
        // Esta regra já estava correta.
        return $user->system_role === 'internal' && $user->can('imoveis_deletar');
    }

    // Os métodos restore e forceDelete podem continuar como 'false' por enquanto,
    // pois não estamos usando essas funcionalidades.
}
