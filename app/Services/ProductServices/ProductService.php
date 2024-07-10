<?php

namespace App\Services\ProductServices;

use Carbon\Carbon;
use App\Models\Product;
use Illuminate\Support\Str;
use App\Models\ProductsMain;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class ProductService
{
    private array $preparedData = [];

    public function __construct(public Product $product)
    {
    }

    public function store(): Product
    {
        return $this->product::create($this->preparedData);
    }

    public function update(): bool
    {
        return $this->product->update($this->preparedData);
    }

    public function destroy(array $ids): int
    {
        return $this->product::destroy($ids);
    }

    public function setProduct(Product $product): self
    {
        $this->product = $product;
        return $this;
    }

    public function getById(int $productID): Product|ModelNotFoundException
    {
        return $this->product::findOrFail($productID);
    }

    public function prepareData(array $variant, ProductsMain $productsMain): self
    {
        $slug = $this->generateSlug($variant['slug']);
        $finalPrice = $this->calculateFinalPrice($productsMain, $variant['additional_price']);

        $this->preparedData = [
            'main_product_id' => $productsMain->id,
            'name' => $variant['name'],
            'variant_name' => $variant['variant_name'],
            'slug' => $slug,
            'additional_price' => $variant['additional_price'],
            'final_price' => $finalPrice,
            'extra_description' => $variant['extra_description'],
            'status' => isset($variant['p_status']) && $variant['p_status'] == '1' ? 1 : 0,
            'publish_date' => isset($variant['publish_date']) ? Carbon::parse($variant['publish_date'])->toDateTimeString() : null
        ];

        return $this;
    }

    private function generateSlug(string $slug): string
    {
        return Str::slug($slug);
    }

    private function calculateFinalPrice(ProductsMain $productsMain, float $additionalPrice): string
    {
        $finalPrice = $productsMain->price + $additionalPrice;
        $finalPrice = number_format($finalPrice, 2);
        return str_replace(',', '', $finalPrice);
    }

    public function getAllActive(): Collection
    {
        return $this->product::query()
            ->with(['activeProductsMain', 'activeProductsMain.brand', 'activeProductsMain.category', 'sizeStock', 'featuredImage'])
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->get();
    }

    public function getSearchProducts(Request $request, array $filterValues): LengthAwarePaginator
    {
        $query = $this->product::query()->with(['activeProductsMain', 'activeProductsMain.category', 'activeProductsMain.brand']);

        if (isset($filterValues['categories'])) {
            $query->whereHas('activeProductsMain.category', function ($q) use ($filterValues) {
                $q->whereIn('slug', $filterValues['categories'])->where('status', 1);
            });
        }

        if (isset($filterValues['brands'])) {
            $query->whereHas('activeProductsMain.brand', function ($q) use ($filterValues) {
                $q->whereIn('slug', $filterValues['brands'])->where('status', 1);
            });
        }

        if (isset($filterValues['genders'])) {
            $query->whereHas('activeProductsMain', function ($q) use ($filterValues) {
                $q->whereIn('gender', $filterValues['genders']);
            });
        }

        if ($request->has('min_price')) {
            $query->where('final_price', '>=', number_format((float)$request->min_price, 2, thousands_separator: ''));
        }

        if ($request->has('max_price')) {
            $query->where('final_price', '<=', number_format((float)$request->max_price, 2, thousands_separator: ''));
        }

        $query = $query->whereHas('activeProductsMain')->where('status', 1);

        if ($request->has('q')) {

            $searchTerm = $request->q;
            $query = $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhereHas('activeProductsMain', function ($q) use ($searchTerm) {
                        $q->where('name', 'LIKE', "%{$searchTerm}%"); // yukardaki seklin kisaltilmisisi js teki temp literals
                    });
            });
        }

        if ($request->has('sort')) {
            $query = match ($request->sort) {
                'id_desc' => $query->orderBy('id', 'DESC'),
                'price_asc' => $query->orderBy('final_price', 'ASC'),
                'price_desc' => $query->orderBy('final_price', 'DESC'),
                default => $query->orderBy('id', 'DESC'),
            };

            // switch case li hali
            /*
            // switch ($request->sort) {
            //     case 'id_desc':
            //         $query = $query->orderBy('id', 'DESC');
            //         break;
            //     case 'price_asc':
            //         $query = $query->orderBy('final_price', 'ASC');
            //         break;
            //     case 'price_desc':
            //         $query = $query->orderBy('final_price', 'DESC');
            //         break;
            //     default:
            //         $query = $query->orderBy('id', 'DESC');
            // }
            */
        }

        return $query->paginate(1);
    }
}