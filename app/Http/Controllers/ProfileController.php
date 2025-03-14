<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use App\Services\ProfileService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
    //todo: passar para service
    public function destroy(int $id): RedirectResponse
    {
        try {
            $this->authorize('delete', User::class);

            ProfileService::destroy($id);

            return Redirect::route('users.index')->with('success', 'Usuário removido com sucesso.');
        } catch (AuthorizationException) {
            return $this->unauthorizedResponse('users.index');
        }
    }

    public function index(): View|RedirectResponse
    {
        try {
            $this->authorize('view', User::class);

            [$users, $permission] = ProfileService::list();

            return view('profiles.index', [
                'users' => $users,
                'permissions' => $permission,
            ]);
        } catch (AuthorizationException) {
            return $this->unauthorizedResponse('dashboard');
        }
    }

    public function updateUser(ProfileUpdateRequest $request, string $userId): RedirectResponse
    {
        try {
            $this->authorize('update', User::class);

            ProfileService::update($userId, $request->validated());

            return Redirect::route('users.index')->with('success', 'Usuário atualizado com sucesso.');
        } catch (AuthorizationException) {
            return $this->unauthorizedResponse('users.index');
        }
    }

    public function create(ProfileRequest $request): RedirectResponse
    {
        try {
            $this->authorize('create', User::class);

            ProfileService::create($request->validated());

            return Redirect::route('users.index')->with('success', 'Usuário cadastrado com sucesso.');
        } catch (AuthorizationException) {
            return $this->unauthorizedResponse('users.index');
        }
    }
}
