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
        return $this->baseAuthorize($user, $model, 'view');
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
        return $this->baseAuthorize($user, $model, 'update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): Response
    {
        return $this->baseAuthorize($user, $model, 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, User $model): Response
    {
        return $this->baseAuthorize($user, $model, 'restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, User $model): Response
    {
        return $this->baseAuthorize($user, $model, 'forceDelete');
    }

    private function baseAuthorize(User $user, User $model, string $action): Response
    {
        if ($user->role === UserRole::Admin || $user->id === $model->id && $action === 'view') {
            return Response::allow();
        }

        if ($user->role === UserRole::Owner) {
            if ($model->role === UserRole::NormalUser) {
                return Response::allow();
            }
            if ($model->role === UserRole::Manager) {
                if ($model->rooms->load(['house' => fn($query) => $query->where('owner_id', $user->id)])->count() > 0) {
                    return Response::allow();
                }
                if ($model->rooms->count() === 0) {
                    return Response::allow();
                }
            }
        }

        if ($user->role === UserRole::Manager && $model->role === UserRole::NormalUser) {
            return Response::allow();
        }

        return Response::deny();
    }
}
