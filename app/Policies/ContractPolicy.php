<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Contract;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ContractPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Owner, UserRole::Manager]) ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Contract $contract): Response
    {
        return $this->baseAuthorize($user, $contract, 'view');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return in_array($user->role, [UserRole::Admin, UserRole::Owner, UserRole::Manager]) ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Contract $contract): Response
    {
        return $this->baseAuthorize($user, $contract, 'update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Contract $contract): Response
    {
        return $this->baseAuthorize($user, $contract, 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Contract $contract): Response
    {
        return $this->baseAuthorize($user, $contract, 'restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Contract $contract): Response
    {
        return $this->baseAuthorize($user, $contract, 'forceDelete');
    }

    private function baseAuthorize(User $user, Contract $contract, string $action): Response
    {
        if ($user->role === UserRole::Admin) {
            return Response::allow();
        }

        if ($user->role === UserRole::Owner) {
            return $contract->room->house->owner_id === $user->id ? Response::allow() : Response::deny();
        }

        if ($user->role === UserRole::Manager && in_array($action, ['view', 'update', 'create'])) {
            return $contract->room->manager_id === $user->id ? Response::allow() : Response::deny();
        }

        return Response::deny();
    }
}
