<?php

namespace App\Policies;

use App\Models\TravelOrder;
use App\Models\User;

class TravelOrderPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->is_admin) {
            return true;
        }

        return null;
    }

    public function view(User $user, TravelOrder $order): bool
    {
        return $user->id === $order->user_id;
    }

    public function update(User $user, TravelOrder $order): bool
    {
        return $user->id === $order->user_id;
    }

    public function approve(User $user, TravelOrder $order): bool
    {
        return false;
    }

    public function cancel(User $user, TravelOrder $order): bool
    {
        return false;
    }
}