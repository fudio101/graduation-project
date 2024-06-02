<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return collect([UserRole::Admin, UserRole::Owner, UserRole::Manager])->contains($user->role) ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $model): Response
    {
        return $this->baseAuthorize($user, $model);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return collect([UserRole::Admin, UserRole::Owner, UserRole::Manager])->contains($user->role) ? Response::allow() : Response::deny();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): Response
    {
        return $this->baseAuthorize($user, $model);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): Response
    {
        return $this->baseAuthorize($user, $model);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): Response
    {
        return $this->baseAuthorize($user, $model);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): Response
    {
        return $this->baseAuthorize($user, $model);
    }

    private function baseAuthorize(User $user, User $model): Response
    {
        if ($user->role === UserRole::Admin) {
            return Response::allow();
        }

        if ($user->role === UserRole::Owner) {
            if ($model->role === UserRole::Owner && $user->id === $model->id) {
                return Response::allow();
            }
            if (in_array($model->role, [UserRole::Manager, UserRole::NormalUser])) {
                return Response::allow();
            }
        }

        if ($user->role === UserRole::Manager) {
            if ($model->role === UserRole::Manager && $user->id === $model->id) {
                return Response::allow();
            }
            if ($model->role === UserRole::NormalUser) {
                return Response::allow();
            }
        }

        if ($user->role === UserRole::NormalUser && $user->id === $model->id) {
            return Response::allow();
        }

        return Response::deny();
    }
}
