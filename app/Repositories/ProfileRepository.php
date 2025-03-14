<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;

class ProfileRepository
{
    public static function list(): Collection
    {
        return User::get();
    }

    public static function create($data): User
    {
        return User::create($data);
    }

    public static function find(int $id): ?User
    {
        return User::find($id);
    }

    public static function update(User $user, array $data): User
    {
        $user->update($data);

        return $user;
    }

    public static function destroy(User $user): bool
    {
        return $user->delete();
    }
}
