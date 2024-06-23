<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\ElectricManager;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ElectricManagerPolicy
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
    public function view(User $user, ElectricManager $electricManager): Response
    {
        return $this->baseAuthorize($user, $electricManager, 'view');
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
    public function update(User $user, ElectricManager $electricManager): Response
    {
        return $this->baseAuthorize($user, $electricManager, 'update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ElectricManager $electricManager): Response
    {
        return $this->baseAuthorize($user, $electricManager, 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ElectricManager $electricManager): Response
    {
        return $this->baseAuthorize($user, $electricManager, 'restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ElectricManager $electricManager): Response
    {
        return $this->baseAuthorize($user, $electricManager, 'forceDelete');
    }

    private function baseAuthorize(User $user, ElectricManager $electricManager, string $action): Response
    {
        if ($user->role === UserRole::Admin) {
            return Response::allow();
        }

        if ($user->role === UserRole::Owner) {
            return $electricManager->house->owner_id === $user->id ? Response::allow() : Response::deny();
        }

        if ($user->role === UserRole::Manager && $action === 'view') {
            return $electricManager->manager_id === $user->id ? Response::allow() : Response::deny();
        }

        return Response::deny();
    }
}
