<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;


class ProductController extends Controller
{
    public function index(): View|RedirectResponse
    {
        try {
            $this->authorize('view', Product::class);

            $products = ProductService::list();

            return view('products.index', [
                'products' => $products,
            ]);
        } catch (AuthorizationException) {
            return $this->unauthorizedResponse('dashboard');
        }
    }

    public function create(ProductRequest $request): RedirectResponse
    {
        try {
            $this->authorize('create', Product::class);

            ProductService::create(
                $request->validated()
            );

            return Redirect::route('products.index')->with('success', 'Produto criado com sucesso.');
        } catch (AuthorizationException) {
            return $this->unauthorizedResponse('products.index');
        }
    }

    public function update(ProductRequest $request, int $id): RedirectResponse
    {
        try {
            $this->authorize('update', Product::class);

            ProductService::update($id, $request->validated());

            return Redirect::route('products.index')->with('success', 'Produto atualizado com sucesso.');
        } catch (AuthorizationException) {
            return $this->unauthorizedResponse('products.index');
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        try {
            $this->authorize('delete', Product::class);

            ProductService::destroy($id);

            return Redirect::route('products.index')->with('success', 'Produto excluido com sucesso.');
        } catch (AuthorizationException) {
            return $this->unauthorizedResponse('products.index');
        }
    }
}
