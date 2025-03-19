<?php

namespace App\Services;

use App\Models\Retailer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class RetailerService
{
    public function store(array $data): Retailer|false
    {
        $link = null;

        try {
            DB::beginTransaction();
            $retailer = Retailer::query()->create($data);
            $link = $data['logo']->store(Retailer::LOGO_PATH, 'public');
            $retailer->logo()->create([
                'link' => $link,
            ]);
            DB::commit();

            return $retailer;

        } catch (Throwable $throwable) {
            DB::rollBack();
            Log::stack(['retailers'])->error($throwable->getMessage());
            if ($link) {
                Storage::disk('public')->delete($link);
            }

            return false;
        }
    }

    public function update(array $data, Retailer $retailer): Retailer|false
    {
        $oldLink = $retailer->logo->link;
        $newLink = null;

        try {
            DB::beginTransaction();
            $retailer->update($data);

            if (isset($data['logo'])) {
                $newLink = $data['logo']->store(Retailer::LOGO_PATH, 'public');
                $retailer->logo()->update(['link' => $newLink]);
                Storage::disk('public')->delete($oldLink);
            }
            DB::commit();

            return $retailer;

        } catch (Throwable $throwable) {
            DB::rollBack();
            Log::stack(['retailers'])->error($throwable->getMessage());
            if ($newLink) {
                Storage::disk('public')->delete($newLink);
            }
            $retailer->logo()->update(['link' => $oldLink]);

            return false;
        }
    }

    public function destroy(Retailer $retailer): bool
    {
        try {
            DB::beginTransaction();
            $link = $retailer->logo->link;
            $retailer->logo()->delete();
            $retailer->delete();
            Storage::disk('public')->delete($link);
            DB::commit();

            return true;

        } catch (Throwable $throwable) {
            DB::rollBack();
            Log::stack(['retailers'])->error($throwable->getMessage());

            return false;
        }
    }
}
