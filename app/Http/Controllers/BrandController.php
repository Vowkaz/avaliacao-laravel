<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use App\Services\BrandService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class BrandController extends Controller
{
    public function index(): View|RedirectResponse
    {
        try {
            $this->authorize('view', Brand::class);

            $brands = BrandService::list();

            return view('brands.index', [
                'brands' => $brands,
            ]);
        } catch (AuthorizationException) {
            return $this->unauthorizedResponse('dashboard');
        }
    }

    public function create(BrandRequest $request): RedirectResponse
    {
        try {
            $this->authorize('create', Brand::class);

            BrandService::create(
                $request->validated()
            );

            return Redirect::route('brands.index')->with('success', 'Marca criada com sucesso.');
        } catch (AuthorizationException) {
            return $this->unauthorizedResponse('brands.index');
        }
    }

    public function update(BrandRequest $request, int $id): RedirectResponse
    {
        try {
            $this->authorize('update', Brand::class);

            BrandService::update($id, $request->validated());

            return Redirect::route('brands.index')->with('success', 'Marca atualizada com sucesso.');
        } catch (AuthorizationException) {
            return $this->unauthorizedResponse('brands.index');
        }
    }

    public function destroy(int $id): RedirectResponse
    {
        try {
            $this->authorize('delete', Brand::class);

            BrandService::destroy($id);

            return Redirect::route('brands.index')->with('success', 'Marca excluida com sucesso.');
        } catch (AuthorizationException) {
            return $this->unauthorizedResponse('brands.index');
        }
    }
}
