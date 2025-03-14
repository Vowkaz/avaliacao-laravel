<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\ProfileRepository;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ProfileService
{
    public static function list(): array
    {
        $commonRole = Role::where('name', User::ROLE_ADMIN)->first();

        $permission = Permission::whereNotIn(
            'id',
            $commonRole->permissions()->pluck('id')
        )->get();

        return [
            ProfileRepository::list(),
            $permission
        ];
    }

    public static function create(array $data): User
    {
        $user = ProfileRepository::create($data);

        self::syncPermission(
            $user,
            Arr::pull($data, 'permissions', []),
            Arr::pull($data, 'role', User::ROLE_COMMON)
        );

        return $user;
    }

    public static function find($id): ?User
    {
        return ProfileRepository::find($id);
    }

    public static function update(int $id, array $data): ?User
    {
        $user = self::find($id);

        if ($user instanceof User) {
            $user = ProfileRepository::update($user, $data);

            self::syncPermission(
                $user,
                Arr::pull($data, 'permissions', []),
                Arr::pull($data, 'role', User::ROLE_COMMON)
            );
        }

        return $user;
    }

    public static function syncPermission(User $user, array $permissions, string $role): void
    {
        $user->syncPermissions($role === User::ROLE_ADMIN ? [] : $permissions);
        $user->syncRoles($role);
    }

    public static function destroy(int $id): bool
    {
        $user = self::find($id);

        return ProfileRepository::destroy($user);
    }
}
