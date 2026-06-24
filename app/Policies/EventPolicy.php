<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Event;

class EventPolicy
{
    /**
     * Anyone can view events
     */
    public function viewAny(?User $user)
    {
        return true;
    }

    public function view(?User $user, Event $event)
    {
        return true;
    }

    /**
     * Only logged-in users (NGO/Admin) can create
     */
    public function create(User $user)
    {
        return in_array($user->role, ['admin', 'ngo_admin']);
    }

    /**
     * Only event owner OR admin can update
     */
    public function update(User $user, Event $event)
    {
        return $user->id === $event->user_id 
            || $user->role === 'admin';
    }

    /**
     * Only admin can delete
     */
    public function delete(User $user, Event $event)
    {
        return $user->role === 'admin';
    }
}