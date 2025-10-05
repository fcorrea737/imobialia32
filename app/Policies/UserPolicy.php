<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determina se o usuário pode ver a LISTA de proprietários.
     */
    public function viewAny(User $user): bool
    {
        // A regra correta: Apenas usuários internos podem ver a lista.
        return $user->system_role === 'internal';
    }

    /**
     * Determina se o usuário pode ver os DETALHES de um usuário específico.
     */
    public function view(User $user, User $model): bool
    {
        // Um usuário interno pode ver qualquer um, ou um usuário pode ver a si mesmo.
        return $user->system_role === 'internal' || $user->id === $model->id;
    }

    /**
     * Determina se o usuário pode CRIAR novos usuários.
     */
    public function create(User $user): bool
    {
        // A regra correta: Apenas usuários internos podem criar.
        return $user->system_role === 'internal';
    }

    /**
     * Determina se o usuário pode ATUALIZAR um usuário.
     */
    public function update(User $user, User $model): bool
    {
        // Apenas usuários internos podem atualizar.
        return $user->system_role === 'internal';
    }

    /**
     * Determina se o usuário pode DELETAR um usuário.
     */
    public function delete(User $user, User $model): bool
    {
        // Apenas usuários internos podem deletar.
        return $user->system_role === 'internal';
    }
}
