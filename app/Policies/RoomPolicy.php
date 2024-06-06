<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Room;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RoomPolicy
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
    public function view(User $user, Room $room): Response
    {
        return $this->baseAuthorize($user, $room, 'view');
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
    public function update(User $user, Room $room): Response
    {
        return $this->baseAuthorize($user, $room, 'update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Room $room): Response
    {
        return $this->baseAuthorize($user, $room, 'delete');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Room $room): Response
    {
        return $this->baseAuthorize($user, $room, 'restore');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Room $room): Response
    {
        return $this->baseAuthorize($user, $room, 'forceDelete');
    }

    private function baseAuthorize(User $user, Room $room, string $action): Response
    {
        if ($user->role === UserRole::Admin) {
            return Response::allow();
        }

        if ($user->role === UserRole::Owner) {
            return $room->house->owner_id === $user->id ? Response::allow() : Response::deny();
        }

        if ($user->role === UserRole::Manager && $action === 'view') {
            return $room->manager_id === $user->id ? Response::allow() : Response::deny();
        }

        return Response::deny();
    }
}
