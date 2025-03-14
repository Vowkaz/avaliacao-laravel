@php use App\Models\User;use Illuminate\Support\Arr; @endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Usuários') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('error') || session('success'))
                <div class="white dark:bg-gray-800 text-gray-900 dark:text-gray-100 p-4 mb-4 rounded">
                    {{ session('error') ?? session('success')}}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl">Usuários Cadastrados</h3>

                        @can('gestao-usuarios')
                            <button
                                x-data @click="$dispatch('open-aside')"
                                class="border rounded-md px-4 py-1.5"
                            >
                                Cadastrar Usuário
                            </button>
                        @endcan
                    </div>
                    <table class="min-w-full table-auto">
                        <thead>
                        <tr>
                            <th class="px-2 py-2 border-b text-start">{{ __('Nome') }}</th>
                            <th class="py-2 border-b text-start">{{ __('Email') }}</th>
                            <th class="py-2 border-b text-start">{{ __('Role') }}</th>
                            <th class="py-2 border-b text-start">{{ __('Data de Criação') }}</th>
                            @can('gestao-usuarios')
                                <th class="py-2 border-b text-start">{{ __('') }}</th>
                            @endcan
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td class="px-2 py-2 border-b">{{ $user->name }}</td>
                                <td class="py-2 border-b">{{ $user->email }}</td>
                                <td class="py-2 border-b">{{ Arr::get(User::ROLE_LABELS, $user->role, User::ROLE_COMMON) }}</td>
                                <td class="py-2 border-b">{{ $user->created_at->format('d/m/Y') }}</td>
                                @can('gestao-usuarios')
                                    <td class="py-2 border-b flex">
                                        <button x-data
                                                @click="$dispatch('open-edit-aside', {
                                                    id: {{ $user->id }},
                                                    name: '{{ $user->name }}',
                                                    email: '{{ $user->email }}',
                                                    role: '{{ $user->role }}',
                                                    permissions: {{ $user->permissions->pluck('name') }}
                                                })"
                                                class="px-2 py-4">
                                            <i class="fas fa-edit"></i> <!-- Ícone de edição -->
                                        </button>

                                        <form action="{{ route('users.delete', $user->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-2 py-4">
                                                <i class="fas fa-remove"></i><!-- Ícone de delete -->
                                            </button>
                                        </form>
                                    </td>
                                @endcan
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <aside x-data="{ open: false, role: 'common' }" x-show="open" @open-aside.window="open = true"
           @close-aside.window="open = false"
           class="fixed inset-0 bg-gray-200/80 dark:bg-gray-900/80 bg-opacity-50 flex items-center justify-center z-50">
        <div class=" rounded-lg shadow-lg mx-auto" style="width: 28rem">
            <div class="bg-white w-full dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold">Cadastrar Usuário</h2>

                        <button x-data @click="$dispatch('close-aside')" class="px-4 py-4">
                            <i class="fas fa-times"></i> <!-- Ícone de "X" -->
                        </button>
                    </div>

                    <form action="{{ route('users.create') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="block">Nome</label>
                            <input type="text" name="name" id="name"
                                   class="w-full px-4 py-2 border rounded bg-white dark:bg-gray-800" required>
                        </div>
                        <div class="mb-4">
                            <label for="email" class="block">E-mail</label>
                            <input type="email" name="email" id="email"
                                   class="w-full px-4 py-2 border rounded bg-white dark:bg-gray-800"
                                   required>
                        </div>
                        <div class="mb-4">
                            <label for="role" class="block">Tipo de Usuário</label>
                            <select
                                name="role"
                                id="role"
                                x-model="role"
                                class="w-full px-4 py-2 border rounded bg-white dark:bg-gray-800"
                            >
                                <option value="common">Comum</option>
                                <option value="admin">Administrador</option>
                            </select>
                        </div>

                        <div x-show="role === 'common'">
                            @foreach($permissions as $permission)
                                <div class="mb-4 block">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                           id="{{ $permission->name }}"
                                           class="mr-2">
                                    <label for="{{ $permission->name }}" class="ml-1">{{ $permission->alias }}</label>
                                </div>
                            @endforeach
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block">Senha</label>
                            <input type="password" name="password" id="password"
                                   class="w-full px-4 py-2 border rounded bg-white dark:bg-gray-800"
                                   required>
                        </div>
                        <div class="mb-4">
                            <label for="password_confirmation" class="block">Confirmar Senha</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                   class="w-full px-4 py-2 border rounded bg-white dark:bg-gray-800" required>
                        </div>

                        <button type="submit"
                                class="w-full px-4 py-2 bg-gray-200 text-gray-900 dark:text-gray-100 dark:bg-gray-900 rounded">
                            Cadastrar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>

    <aside x-data="{
                openEdit: false,
                id: null,
                name: '',
                email: '',
                role: 'common',
                permissions: []
            }"
           x-show="openEdit"
           @open-edit-aside.window="openEdit = true; id = $event.detail.id; name = $event.detail.name; email = $event.detail.email; role = $event.detail.role; permissions = $event.detail.permissions;"
           @close-edit-aside.window="openEdit = false"
           class="fixed inset-0 bg-gray-200/80 dark:bg-gray-900/80 bg-opacity-50 flex items-center justify-center z-50"
    >
        <div class="rounded-lg shadow-lg mx-auto" style="width: 28rem">
            <div class="bg-white w-full dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold">Editar Usuário</h2>

                        <button x-data @click="$dispatch('close-edit-aside')" class="px-4 py-4">
                            <i class="fas fa-times"></i> <!-- Ícone de "X" -->
                        </button>
                    </div>

                    <form x-data :action="'{{ route('users.update', '') }}' + '/' + id"  method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="block">Nome</label>
                            <input type="text" name="name" id="name" x-model="name"
                                   class="w-full px-4 py-2 border rounded bg-white dark:bg-gray-800" required>
                        </div>
                        <div class="mb-4">
                            <label for="email" class="block">E-mail</label>
                            <input type="email" name="email" id="email" x-model="email"
                                   class="w-full px-4 py-2 border rounded bg-white dark:bg-gray-800" required>
                        </div>

                        <div class="mb-4">
                            <label for="role" class="block">Tipo de Usuário</label>
                            <select
                                name="role"
                                id="role"
                                x-model="role"
                                class="w-full px-4 py-2 border rounded bg-white dark:bg-gray-800"
                            >
                                <option value="common">Comum</option>
                                <option value="admin">Administrador</option>
                            </select>
                        </div>

                        <div x-show="role === 'common'">
                            @foreach($permissions as $permission)
                                <div class="mb-4 block">
                                    <input
                                        id="{{ $permission->name }}"
                                        type="checkbox"
                                        name="permissions[]"
                                        value="{{ $permission->name }}"
                                        class="mr-2"
                                        :checked="permissions.includes('{{ $permission->name }}')"
                                    >
                                    <label for="{{ $permission->name }}" class="ml-1">{{ $permission->alias }}</label>
                                </div>
                            @endforeach
                        </div>

                        <button
                            type="submit"
                            class="w-full px-4 py-2 bg-gray-200 text-gray-900 dark:text-gray-100 dark:bg-gray-900 rounded"
                        >
                            Salvar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </aside>
</x-app-layout>
