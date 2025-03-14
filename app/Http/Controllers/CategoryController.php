<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View|RedirectResponse
    {
        try {
            $this->authorize('view', Category::class);

            $categories = CategoryService::list();

            return view('categories.index', [
                'categories' => $categories,
            ]);
        } catch (AuthorizationException) {
            return $this->unauthorizedResponse('dashboard');
        }
    }

    public function create(CategoryRequest $request): RedirectResponse
    {
        try {
            $this->authorize('create', Category::class);

            CategoryService::create(
                $request->validated()
            );

            return Redirect::route('categories.index')->with('success', 'Categoria criada com sucesso.');
        } catch (AuthorizationException) {
            return $this->unauthorizedResponse('categories.index');
        }
    }

    public function update(CategoryRequest $request, int $id): RedirectResponse
    {
        try {
            $this->authorize('update', Category::class);

            CategoryService::update($id, $request->validated());

            return Redirect::route('categories.index')->with('success', 'Categoria atualizada com sucesso.');
        } catch (AuthorizationException) {
            return $this->unauthorizedResponse('categories.index');
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        try {
            $this->authorize('delete', Category::class);

            CategoryService::destroy($id);

            return Redirect::route('categories.index')->with('success', 'Categoria excluida com sucesso.');
        } catch (AuthorizationException) {
            return $this->unauthorizedResponse('categories.index');
        }
    }
}
