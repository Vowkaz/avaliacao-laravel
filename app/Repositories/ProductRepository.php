<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Support\Collection;

class ProductRepository
{
    public static function list(): Collection
    {
        return Product::get();
    }

    public static function create($data): Product
    {
        return Product::create($data);
    }

    public static function find(int $id): ?Product
    {
        return Product::find($id);
    }

    public static function update(Product $product, array $data): Product
    {
        $product->update($data);

        return $product;
    }

    public static function destroy(Product $product): bool
    {
        return $product->delete();
    }
}
