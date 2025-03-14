<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(int $id): RedirectResponse
    {
        try {
            $this->authorize('delete', User::class);


            $user = User::findOrFail($id);

            $user->delete();
            return Redirect::route('users.index')->with('success', 'Usuário removido com sucesso.');
        } catch (AuthorizationException) {
            return $this->unauthorizedResponse('users.index');
        }
    }

    public function index(): View|RedirectResponse
    {
        try {
            $this->authorize('view', User::class);

            $users = User::all();
            $commonRole = Role::where('name', User::ROLE_ADMIN)->first();
            $permission = Permission::whereNotIn(
                'id',
                $commonRole->permissions()->pluck('id')
            )->get();

            return view('profiles.index', [
                'users' => $users,
                'permissions' => $permission,
            ]);
        } catch (AuthorizationException) {
            return $this->unauthorizedResponse('dashboard');
        }
    }

    public function updateUser(Request $request, string $userId): RedirectResponse
    {
        try {
            $this->authorize('update', User::class);

            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class . ',email,' . $userId],
                'role' => ['required', 'string', 'in:' . implode(',', [User::ROLE_ADMIN, User::ROLE_COMMON])],
                'permissions' => ['nullable', 'array'],
                'permissions.*' => ['exists:permissions,name'],
            ]);

            $user = User::query()
                ->findOrFail($userId);

            $user->update([
                'name' => $request->name,
                'role' => $request->role,
                'email' => $request->email,
            ]);

            $user->syncPermissions($request->role === User::ROLE_ADMIN ? [] : $request->permissions);

            if ($user instanceof User) {
                $user->syncRoles($request->role);
            }

            event(new Registered($user));

            return Redirect::route('users.index')->with('success', 'Usuário atualizado com sucesso.');
        } catch (AuthorizationException) {
            return $this->unauthorizedResponse('users.index');
        }
    }
    public function create(Request $request): RedirectResponse
    {
        try {
            $this->authorize('create', User::class);

            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'confirmed', Password::defaults()],
                'role' => ['required', 'string', 'in:' . implode(',', [User::ROLE_ADMIN, User::ROLE_COMMON])],
                'permissions' => ['nullable', 'array'],
                'permissions.*' => ['exists:permissions,name'],
            ]);


            $user = User::create([
                'name' => $request->name,
                'role' => $request->role,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            if ($user instanceof User) {
                $user->assignRole($request->role);
            }

            if ($request->has('permissions')) {
                $user->syncPermissions($request->permissions);
            }

            event(new Registered($user));

            return Redirect::route('users.index')->with('success', 'Usuário cadastrado com sucesso.');
        } catch (AuthorizationException) {
            return $this->unauthorizedResponse('users.index');
        }
    }
}
