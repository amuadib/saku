<?php

namespace App\Policies;

use App\Models\Tagihan;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TagihanPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isTataUsaha();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Tagihan $tagihan): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->isTataUsaha() and $tagihan->siswa->lembaga_id == $user->authable->lembaga_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isTataUsaha();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Tagihan $tagihan): bool
    {
        return $user->isTataUsaha();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Tagihan $tagihan): bool
    {
        return $user->isTataUsaha();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Tagihan $tagihan): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Tagihan $tagihan): bool
    {
        //
    }
}
