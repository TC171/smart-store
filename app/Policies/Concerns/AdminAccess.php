<?php

namespace App\Policies\Concerns;

use App\Models\User;

trait AdminAccess
{
    protected function isAdmin(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff'], true);
    }

    protected function isShipper(User $user): bool
    {
        return $user->role === 'shipper';
    }
}

