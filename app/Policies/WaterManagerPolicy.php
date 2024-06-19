<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;
use App\Models\WaterManager;
use Illuminate\Auth\Access\Response;

class WaterManagerPolicy
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
    public function view(User $user, WaterManager $waterManager): Response
    {
        return $this->baseAuthorize($user, $waterManager, 'view');
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
    public function update(User $user, WaterManager $waterManager): Response
    {
        return $this->baseAuthorize($user, $waterManager, 'update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WaterManager $waterManager): Response
    {
        return $this->baseAuthorize($user, $waterManager, 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, WaterManager $waterManager): Response
    {
        return $this->baseAuthorize($user, $waterManager, 'restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, WaterManager $waterManager): Response
    {
        return $this->baseAuthorize($user, $waterManager, 'forceDelete');
    }

    private function baseAuthorize(User $user, WaterManager $waterManager, string $action): Response
    {
        if ($user->role === UserRole::Admin) {
            return Response::allow();
        }

        if ($user->role === UserRole::Owner) {
            return $waterManager->house->owner_id === $user->id ? Response::allow() : Response::deny();
        }

        if ($user->role === UserRole::Manager && $action === 'view') {
            return $waterManager->manager_id === $user->id ? Response::allow() : Response::deny();
        }

        return Response::deny();
    }
}
