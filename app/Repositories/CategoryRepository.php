<?php

namespace App\Repositories;

use App\Models\Category;
use Illuminate\Support\Collection;

class CategoryRepository
{
    public static function list(): Collection
    {
        return Category::get();
    }

    public static function create($data): Category
    {
        return Category::create($data);
    }

    public static function find(int $id): ?Category
    {
        return Category::find($id);
    }

    public static function update(Category $category, array $data): Category
    {
        $category->update($data);

        return $category;
    }

    public static function destroy(Category $category): bool
    {
        return $category->delete();
    }
}
