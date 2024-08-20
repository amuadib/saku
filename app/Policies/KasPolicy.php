<?php

namespace App\Policies;

use App\Models\Kas;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class KasPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() or $user->isKepala();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Kas $kas): bool
    {
        return $user->isAdmin() or ($user->isKepala() and $user->authable->lembaga_id == $kas->lembaga_id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() or $user->isKepala();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Kas $kas): bool
    {
        return $user->isAdmin() or ($user->isKepala() and $user->authable->lembaga_id == $kas->lembaga_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Kas $kas): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Kas $kas): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Kas $kas): bool
    {
        //
    }
}
