<?php

namespace App\Services;

use App\Models\Brand;
use App\Repositories\BrandRepository;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class BrandService
{
    public static function list(): Collection
    {
        return BrandRepository::list();
    }

    public static function create(array $data): Brand
    {
        return BrandRepository::create([
            'name' => Arr::get($data, 'name'),
        ]);
    }

    public static function find($id): ?Brand
    {
        return BrandRepository::find($id);
    }

    public static function update(int $id, array $data): Brand
    {
        $brand = self::find($id);

        return BrandRepository::update($brand, $data);
    }

    public static function destroy(int $id): bool
    {
        $brand = self::find($id);

        return BrandRepository::destroy($brand);
    }
}
