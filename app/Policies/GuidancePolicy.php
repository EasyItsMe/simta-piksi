<?php

namespace App\Policies;

use App\Models\Guidance;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class GuidancePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Guidance $guidance): bool
    {
        if ($user->isAdmin()) return true;
        if ($user->isMahasiswa() && $guidance->thesis->user_id === $user->id) return true;
        if ($user->isDosen() && $guidance->thesis->advisors()->where('dosen_id', $user->id)->exists()) return true;
        return false;
    }

    public function create(User $user): bool
    {
        return $user->isMahasiswa();
    }

    public function update(User $user, Guidance $guidance): bool
    {
        if ($user->isAdmin()) return true;
        if ($user->isMahasiswa() && $guidance->thesis->user_id === $user->id && $guidance->status === 'submitted') return true;
        if ($user->isDosen() && $guidance->thesis->advisors()->where('dosen_id', $user->id)->exists()) return true;
        return false;
    }

    public function delete(User $user, Guidance $guidance): bool
    {
        return $user->isAdmin() || ($user->isMahasiswa() && $guidance->thesis->user_id === $user->id && $guidance->status === 'submitted');
    }

    public function restore(User $user, Guidance $guidance): bool
    {
        return $user->isAdmin();
    }

    public function forceDelete(User $user, Guidance $guidance): bool
    {
        return $user->isAdmin();
    }
}
