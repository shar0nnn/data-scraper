<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ProductService
{
    public function store(array $data): Product|false
    {
        $links = [];

        try {
            DB::beginTransaction();
            $product = Product::query()->create($data);

            foreach ($data['images'] as $image) {
                $links[] = $image->store(Product::IMAGES_PATH, 'public');
            }

            $product->images()->createMany(
                array_map(fn($link) => ['link' => $link], $links)
            );

            if (isset($data['retailers'])) {
                $product->retailers()->attach(
                    collect($data['retailers'])->mapWithKeys(function ($retailer) {
                        return [$retailer['id'] => [
                            'url' => $retailer['url'],
                        ]];
                    })
                );
            }
            DB::commit();

            return $product;

        } catch (Throwable $throwable) {
            DB::rollBack();
            Log::stack(['products'])->error($throwable->getMessage());
            if ($links) {
                foreach ($links as $link) {
                    Storage::disk('public')->delete($link);
                }
            }

            return false;
        }
    }

    public function update(array $data, Product $product): Product|false
    {
        $oldImages = $product->images;
        $newImages = [];

        try {
            DB::beginTransaction();
            $product->update($data);

            if (isset($data['images'])) {
                $product->images()->delete();

                foreach ($data['images'] as $image) {
                    $newImages[] = $image->store(Product::IMAGES_PATH, 'public');
                }

                $product->images()->createMany(
                    array_map(fn($link) => ['link' => $link], $newImages)
                );

                foreach ($oldImages as $image) {
                    Storage::disk('public')->delete($image->link);
                }
            }

            if (isset($data['retailers'])) {
                $newRetailers = collect($data['retailers'])->pluck('id')->toArray();

                $removedRetailers = $product->retailers()
                    ->whereNotIn('retailers.id', $newRetailers)
                    ->pluck('retailers.id')
                    ->toArray();

                if (! empty($removedRetailers)) {
                    $product->scrapedImages()
                        ->whereIn('product_retailer.retailer_id', $removedRetailers)
                        ->delete();

                    $product->scrapedProducts()
                        ->whereIn('product_retailer.retailer_id', $removedRetailers)
                        ->delete();
                }

                $product->retailers()->sync(
                    collect($data['retailers'])->mapWithKeys(function ($retailer) {
                        return [$retailer['id'] => [
                            'url' => $retailer['url'],
                        ]];
                    })
                );
            }
            DB::commit();

            return $product;

        } catch (Throwable $throwable) {
            DB::rollBack();
            Log::channel('products')->error($throwable->getMessage());
            if ($newImages) {
                foreach ($newImages as $image) {
                    Storage::disk('public')->delete($image->link);
                }
            }

            return false;
        }
    }

    public function destroy(Product $product): bool
    {
        try {
            $images = $product->images;

            DB::beginTransaction();
            $product->images()->delete();
            $product->scrapedImages()->delete();
            $product->scrapedProducts()->delete();
            $product->retailers()->detach();
            $product->delete();
            DB::commit();

            foreach ($images as $image) {
                Storage::disk('public')->delete($image->link);
            }

            return true;

        } catch (Throwable $throwable) {
            DB::rollBack();
            Log::stack(['products'])->error($throwable->getMessage());

            return false;
        }
    }
}
