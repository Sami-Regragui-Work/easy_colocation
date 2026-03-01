<?php

namespace App\Policies;

use App\Models\Colocation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ColocationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function can_view_colocation(User $user, Colocation $colocation): Response
    {
        $isMember = $colocation->members()->where('user_id', $user->id)->whereNull('left_at')->exists();
        $isOrWasOwner = $colocation->owner_id === $user->id;

        return ($isMember || $isOrWasOwner)
            ? Response::allow()
            : Response::denyWithStatus(403, 'You are not in this colocation.');
    }

    /**
     * Determine whether the user can create models.
     */
    public function can_create_colocation(User $user): Response
    {
        return $user->exists
            ? Response::allow()
            : Response::denyWithStatus(403, 'Unauthorized.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function can_update_colocation(User $user, Colocation $colocation): Response
    {
        $isOwner = $colocation->owner_id === $user->id;

        return $isOwner
            ? Response::allow()
            : Response::denyWithStatus(403, 'Only the owner can update the colocation.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function can_delete_colocation(User $user, Colocation $colocation): Response
    {
        $isOwner = $colocation->owner_id === $user->id;

        if (!$isOwner) {
            return Response::denyWithStatus(403, 'Only the owner can delete the colocation.');
        }

        if ($colocation->status !== 'cancelled') {
            return Response::deny('You must cancel the colocation first.');
        }

        if ($colocation->activeMembers()->count() > 1) {
            return Response::deny('You must remove all members before deleting.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Colocation $colocation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Colocation $colocation): bool
    {
        return false;
    }

    public function can_cancel_colocation(User $user, Colocation $colocation): Response
    {
        $isOwner = $colocation->owner_id === $user->id;

        if (!$isOwner) {
            return Response::denyWithStatus(403, 'Only the owner can cancel the colocation.');
        }

        if ($colocation->activeMembers()->count() > 1) {
            return Response::deny('You must remove all members before cancelling.');
        }

        return Response::allow();
    }

    public function can_invite_member(User $user, Colocation $colocation): Response
    {
        $isOwner = $colocation->owner_id === $user->id;

        return $isOwner
            ? Response::allow()
            : Response::denyWithStatus(403, 'Only the owner can invite members.');
    }

    public function can_remove_member(User $user, Colocation $colocation, User $member): Response
    {
        $isOwner = $colocation->owner_id === $user->id;

        if (!$isOwner) {
            return Response::denyWithStatus(403, 'Only the owner can remove members.');
        }

        if ($member->id === $user->id) {
            return Response::deny('You cannot remove yourself this way. Use cancel or quit.');
        }

        return Response::allow();
    }

    public function can_quit_colocation(User $user, Colocation $colocation): Response
    {
        $memberOfColoc = $colocation->members()->where('user_id', $user->id)->whereNull('left_at')->exists();

        $role = $user->whichRole($colocation);

        if (!$memberOfColoc || $role !== 'member') {
            return Response::denyWithStatus(403, 'Only an active member (not owner) can quit.');
        }

        return Response::allow();
    }
}
