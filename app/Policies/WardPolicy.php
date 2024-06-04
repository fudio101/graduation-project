<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\Ward;
use Illuminate\Auth\Access\Response;

class WardPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $user->role === UserRole::Admin ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ward $ward): Response
    {
        return $user->role === UserRole::Admin ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->role === UserRole::Admin ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ward $ward): Response
    {
        return $user->role === UserRole::Admin ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ward $ward): Response
    {
        return $user->role === UserRole::Admin ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Ward $ward): Response
    {
        return $user->role === UserRole::Admin ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Ward $ward): Response
    {
        return $user->role === UserRole::Admin ? Response::allow() : Response::deny();
    }
}
