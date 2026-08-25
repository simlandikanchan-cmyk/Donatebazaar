<?php

namespace App\Policies;

use App\Models\Donation;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DonationReceiptPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Donation $donation): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        return $donation->user_id === $user->id;
    }

    public function download(User $user, Donation $donation): bool
    {
        if ($this->isAdmin($user)) {
            return true;
        }

        return $donation->user_id === $user->id;
    }

    private function isAdmin(User $user): bool
    {
        return $user->role === 'admin';
    }
}
