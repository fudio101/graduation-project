<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\ContractEnd;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ContractEndPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Owner]) ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ContractEnd $contractEnd): Response
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Owner]) ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Owner]) ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ContractEnd $contractEnd): Response
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Owner]) ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ContractEnd $contractEnd): Response
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Owner]) ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ContractEnd $contractEnd): Response
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Owner]) ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ContractEnd $contractEnd): Response
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Owner]) ? Response::allow() : Response::deny();
    }
}
