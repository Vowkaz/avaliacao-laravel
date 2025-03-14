<?php

namespace App\Repositories;

use App\Models\Brand;
use Illuminate\Support\Collection;

class BrandRepository
{
    public static function list(): Collection
    {
        return Brand::get();
    }

    public static function create($data): Brand
    {
        return Brand::create($data);
    }

    public static function find(int $id): ?Brand
    {
        return Brand::find($id);
    }

    public static function update(Brand $brand, array $data): Brand
    {
        $brand->update($data);

        return $brand;
    }

    public static function destroy(Brand $brand): bool
    {
        return $brand->delete();
    }
}
