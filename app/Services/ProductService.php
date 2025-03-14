<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class ProductService
{
    public static function list(): Collection
    {
        return ProductRepository::list();
    }

    public static function create(array $data): Product
    {
        return ProductRepository::create([
            'name' => Arr::get($data, 'name'),
        ]);
    }

    public static function find($id): ?Product
    {
        return ProductRepository::find($id);
    }

    public static function update(int $id, array $data): Product
    {
        $product = self::find($id);

        return ProductRepository::update($product, $data);
    }

    public static function destroy(int $id): bool
    {
        $product = self::find($id);

        return ProductRepository::destroy($product);
    }
}
