<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use App\Policies\Concerns\AdminAccess;

class OrderPolicy
{
    use AdminAccess;

    public function before(?User $user, string $ability): ?bool
    {
        if ($user && $this->isAdmin($user)) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->role === 'customer';
    }

    public function view(User $user, Order $order): bool
    {
        return $user->role === 'customer' && $order->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'customer';
    }

    public function update(User $user, Order $order): bool
    {
        return false;
    }

    public function delete(User $user, Order $order): bool
    {
        return false;
    }
}

