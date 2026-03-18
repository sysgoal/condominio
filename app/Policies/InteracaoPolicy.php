<?php

namespace App\Policies;

use App\Models\Interacao;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InteracaoPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Interacao $interacao): bool
    {
        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Interacao $interacao): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Interacao $interacao): bool
    {
        // 1. Se for Síndico ou Administrador, pode excluir qualquer uma
        if ($user->hasAnyRole(['sindico', 'admin'])) {
            return true;
        }
    
        // 2. Se for o dono da publicação, também pode excluir
        return $user->id === $interacao->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Interacao $interacao): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Interacao $interacao): bool
    {
        return false;
    }
}
