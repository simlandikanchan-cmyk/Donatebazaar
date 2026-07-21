<?php

namespace App\Services;

use App\Models\User;

interface NotificationService
{
    public function send(string $type, User $user, array $data = []): void;
}
