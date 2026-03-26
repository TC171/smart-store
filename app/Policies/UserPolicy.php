<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\AdminAccess;

class UserPolicy
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
        return false;
    }

    public function view(User $user, User $model): bool
    {
        return $user->role === 'customer' && $user->id === $model->id;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, User $model): bool
    {
        return $user->role === 'customer' && $user->id === $model->id;
    }

    public function delete(User $user, User $model): bool
    {
        return false;
    }
}

