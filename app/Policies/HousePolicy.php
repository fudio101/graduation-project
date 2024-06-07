<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\House;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class HousePolicy
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
    public function view(User $user, House $house): Response
    {
        return $this->baseAuthorize($user, $house);
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
    public function update(User $user, House $house): Response
    {
        return $this->baseAuthorize($user, $house);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, House $house): Response
    {
        return $this->baseAuthorize($user, $house);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, House $house): Response
    {
        return $this->baseAuthorize($user, $house);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, House $house): Response
    {
        return $this->baseAuthorize($user, $house);
    }

    private function baseAuthorize(User $user, House $house): Response
    {
        if ($user->role === UserRole::Admin) {
            return Response::allow();
        }

        if ($user->role === UserRole::Owner) {
            return $house->owner_id === $user->id ? Response::allow() : Response::deny();
        }

        return Response::deny();
    }
}
