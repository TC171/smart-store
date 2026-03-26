<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use App\Policies\Concerns\AdminAccess;

class ProductPolicy
{
    use AdminAccess;

    public function before(?User $user, string $ability): ?bool
    {
        if ($user && $this->isAdmin($user)) {
            return true;
        }

        return null;
    }

    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Product $product): bool
    {
        return (bool) $product->status;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Product $product): bool
    {
        return false;
    }

    public function delete(User $user, Product $product): bool
    {
        return false;
    }
}

