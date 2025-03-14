<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Categorias') }}
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
                        <h3 class="text-xl">Categorias Cadastradas</h3>

                        @can('gestao-categorias')
                            <button
                                x-data @click="$dispatch('open-aside')"
                                class="border rounded-md px-4 py-1.5"
                            >
                                Cadastrar Categoria
                            </button>
                        @endcan
                    </div>
                    <table class="min-w-full table-auto">
                        <thead>
                        <tr>
                            <th class="px-2 py-2 border-b text-start">{{ __('Nome') }}</th>
                            <th class="py-2 border-b text-start">{{ __('Data de Criação') }}</th>
                            @can('gestao-categorias')
                                <th class="py-2 border-b text-start">{{ __('') }}</th>
                            @endcan
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td class="px-2 py-2 border-b">{{ $category->name }}</td>
                                <td class="py-2 border-b">{{ $category->created_at->format('d/m/Y') }}</td>
                                @can('gestao-categorias')
                                    <td class="py-2 border-b flex">
                                        <button x-data
                                                @click="$dispatch('open-edit-aside', {
                                                    id: {{ $category->id }},
                                                    name: '{{ $category->name }}',
                                                })"
                                                class="px-2 py-4">
                                            <i class="fas fa-edit"></i> <!-- Ícone de edição -->
                                        </button>

                                        <form action="{{ route('categories.delete', $category->id) }}" method="POST">
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

    <aside x-data="{ open: false }" x-show="open" @open-aside.window="open = true"
           @close-aside.window="open = false"
           class="fixed inset-0 bg-gray-200/80 dark:bg-gray-900/80 bg-opacity-50 flex items-center justify-center z-50">
        <div class=" rounded-lg shadow-lg mx-auto" style="width: 28rem">
            <div class="bg-white w-full dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold">Cadastrar Categoria</h2>

                        <button x-data @click="$dispatch('close-aside')" class="px-4 py-4">
                            <i class="fas fa-times"></i> <!-- Ícone de "X" -->
                        </button>
                    </div>

                    <form action="{{ route('categories.create') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="block">Nome</label>
                            <input type="text" name="name" id="name"
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
                        <h2 class="text-xl font-semibold">Editar Categoria</h2>

                        <button x-data @click="$dispatch('close-edit-aside')" class="px-4 py-4">
                            <i class="fas fa-times"></i> <!-- Ícone de "X" -->
                        </button>
                    </div>

                    <form x-data :action="'{{ route('categories.update', '') }}' + '/' + id" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="name" class="block">Nome</label>
                            <input type="text" name="name" id="name" x-model="name"
                                   class="w-full px-4 py-2 border rounded bg-white dark:bg-gray-800" required>
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
