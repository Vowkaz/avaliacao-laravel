<?php

namespace App\Services;

use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class CategoryService
{
    public static function list(): Collection
    {
        return CategoryRepository::list();
    }

    public static function create(array $data): Category
    {
        return CategoryRepository::create([
            'name' => Arr::get($data, 'name'),
        ]);
    }

    public static function find($id): ?Category
    {
        return CategoryRepository::find($id);
    }

    public static function update(int $id, array $data): Category
    {
        $product = self::find($id);

        return CategoryRepository::update($product, $data);
    }

    public static function destroy(int $id): bool
    {
        $product = self::find($id);

        return CategoryRepository::destroy($product);
    }
}
