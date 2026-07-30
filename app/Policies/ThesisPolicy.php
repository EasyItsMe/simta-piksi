<?php

namespace App\Policies;

use App\Models\Thesis;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ThesisPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Controlled by controller/query
    }

    public function view(User $user, Thesis $thesis): bool
    {
        if ($user->isAdmin()) return true;
        if ($user->isMahasiswa() && $thesis->user_id === $user->id) return true;
        if ($user->isDosen() && $thesis->advisors()->where('dosen_id', $user->id)->exists()) return true;
        return false;
    }

    public function create(User $user): bool
    {
        return $user->isMahasiswa();
    }

    public function update(User $user, Thesis $thesis): bool
    {
        if ($user->isAdmin()) return true;
        if ($user->isMahasiswa() && $thesis->user_id === $user->id && $thesis->status === 'pending') return true;
        return false;
    }

    public function delete(User $user, Thesis $thesis): bool
    {
        return $user->isAdmin() || ($user->isMahasiswa() && $thesis->user_id === $user->id && $thesis->status === 'pending');
    }

    public function restore(User $user, Thesis $thesis): bool
    {
        return $user->isAdmin();
    }

    public function forceDelete(User $user, Thesis $thesis): bool
    {
        return $user->isAdmin();
    }
}
