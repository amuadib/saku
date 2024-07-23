<?php

namespace App\Policies;

use App\Models\Barang;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class BarangPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isPetugas();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Barang $barang): bool
    {
        return $user->isAdmin() or ($user->isPetugas() and $user->authable->lembaga_id == $barang->lembaga_id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isPetugas();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Barang $barang): bool
    {
        return $user->isAdmin() or ($user->isPetugas() and $user->authable->lembaga_id == $barang->lembaga_id);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Barang $barang): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Barang $barang): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Barang $barang): bool
    {
        //
    }
}
