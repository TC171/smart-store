<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;
use App\Policies\Concerns\AdminAccess;

class ReviewPolicy
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

    public function view(User $user, Review $review): bool
    {
        return $user->role === 'customer' && $review->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->role === 'customer';
    }

    public function update(User $user, Review $review): bool
    {
        return $user->role === 'customer' && $review->user_id === $user->id;
    }

    public function delete(User $user, Review $review): bool
    {
        return $user->role === 'customer' && $review->user_id === $user->id;
    }

    public function approve(User $user, Review $review): bool
    {
        return false;
    }

    public function reject(User $user, Review $review): bool
    {
        return false;
    }
}

