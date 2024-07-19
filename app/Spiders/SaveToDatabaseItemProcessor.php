<?php

namespace App\Spiders;

use Illuminate\Support\Str;
use App\Services\CategoryService;
use RoachPHP\Support\Configurable;
use RoachPHP\ItemPipeline\ItemInterface;
use RoachPHP\ItemPipeline\Processors\ItemProcessorInterface;


class SaveToDatabaseItemProcessor implements ItemProcessorInterface
{
    use Configurable;

    public function __construct(public CategoryService $categoryService)
    {
    }

    public function processItem(ItemInterface $data): ItemInterface
    {
        $arr = ['Kadin', 'Erkek', 'Cocuk'];

        $categories = array_values($data['categories']);

        foreach ($arr as $item) {
            $mainCategory = $this->categoryService->setPrepareData([
                'name' => $item,
                'status' => 1,
                'slug' => Str::slug($item),
            ])->create();

            foreach ($categories as $subCat)
                if (!str_contains($subCat, '&')) {
                    try {
                        $this->categoryService->setPrepareData([
                            'name' => "{$item} {$subCat}",
                            'status' => 1,
                            'slug' => Str::slug("{$item} {$subCat}"),
                            'parent_id' => $mainCategory->id,
                        ])->create();
                    } catch (\Throwable $th) {
                        dd($th, $subCat);
                    }
                }
        }

        return $data;
    }
}