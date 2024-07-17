<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Discounts;
use App\Models\ProductTypes;
use App\Enums\DiscountTypeEnum;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DiscountService
{
    private array $prepareData = [];

    public function __construct(public Discounts $discount, public FilterService $filterService)
    {
    }

    public function getFilters(): array
    {
        $types = ['all' => 'Tumu'] +  getAllDiscountTypes();

        return [
            'name' => [
                'label' => 'Indirim Adi',
                'type' => 'text',
                'column' => 'name',
                'operator' => 'like',
            ],
            'value' => [
                'label' => 'Indirim Degeri',
                'type' => 'text',
                'column' => 'value',
                'operator' => 'like',
            ],
            'minimum_spend' => [
                'label' => 'Minimum Harcama Degeri',
                'type' => 'text',
                'column' => 'minimum_spend',
                'operator' => 'like',
            ],
            'type' => [
                'label' => 'Indirim Turu',
                'type' => 'select',
                'column' => 'type',
                'operator' => '=',
                'options' => $types,
            ],
            'status' => [
                'label' => 'Durum',
                'type' => 'select',
                'column' => 'status',
                'operator' => '=',
                'options' => ['all' => 'Tumu', 'Pasif', 'Aktif'],
            ],
            'order_by' => [
                'label' => 'Siralama Turu',
                'type' => 'select',
                'column' => 'order_by',
                'operator' => '',
                'options' => [
                    'id' => 'ID',
                    'name' => 'Indirim Adi',
                    'status' => 'Durum',
                    'value' => 'Indirim Degeri',
                    'type' => 'Indirim Turu',
                    'start_date' => 'Indirim Baslangic Tarihi',
                    'end_date' => 'Indirim Bitis Tarihi',
                ],
            ],
            'order_direction' => [
                'label' => 'Siralama Yonu',
                'type' => 'select',
                'column' => 'order_direction',
                'operator' => '',
                'options' => [
                    'asc' => 'A-Z',
                    'desc' => 'Z-A',
                ],
            ],
        ];
    }

    public function getFiltersForProduct(): array
    {
        $categories = Category::all()->pluck('name', 'id')->toArray();
        $categories = ['all' => 'Tumu'] + $categories;

        $brands = Brand::all()->pluck('name', 'id')->toArray();
        $brands = ['all' => 'Tumu'] + $brands;

        $types = ProductTypes::all()->pluck('name', 'id')->toArray();
        $types = ['all' => 'Tumu'] + $types;

        return [
            'type_id' => [
                'label' => 'Urun Turu',
                'type' => 'select',
                'column' => 'type_id',
                'column_live' => 'type_id',
                'table' => 'products_main',
                'operator' => '=',
                'options' => $types,
            ],
            'category_id' => [
                'label' => 'Kategori',
                'type' => 'select',
                'column' => 'category_id',
                'column_live' => 'category_id',
                'table' => 'products_main',
                'operator' => '=',
                'options' => $categories,
            ],
            'brand_id' => [
                'label' => 'Marka',
                'type' => 'select',
                'column' => 'brand_id',
                'column_live' => 'brand_id',
                'table' => 'products_main',
                'operator' => '=',
                'options' => $brands,
            ],
            'gender' => [
                'label' => 'Cinsiyet',
                'type' => 'select',
                'column' => 'gender',
                'column_live' => 'gender',
                'table' => 'products_main',
                'operator' => '=',
                'options' => ['all' => 'Tumu', 'Kadin', 'Erkek'],
            ],
            'status' => [
                'label' => 'Durum',
                'type' => 'select',
                'column' => 'status',
                'column_live' => 'status',
                'table' => 'products_main',
                'operator' => '=',
                'options' => ['all' => 'Tumu', 'Pasif', 'Aktif'],
            ],
            'product_name' => [
                'label' => 'Urun Adi(Varyant)',
                'type' => 'text',
                'column' => 'product_name',
                'column_live' => 'name',
                'table' => 'products',
                'operator' => 'like',
            ],
            'final_price_min' => [
                'label' => 'Fiyat(min)',
                'type' => 'number',
                'column' => 'final_price_min',
                'column_live' => 'final_price',
                'table' => 'products',
                'operator' => '>=',
            ],
            'final_price_max' => [
                'label' => 'Fiyat(max)',
                'type' => 'number',
                'column' => 'final_price_max',
                'column_live' => 'final_price',
                'table' => 'products',
                'operator' => '<=',
            ],
            'order_by' => [
                'label' => 'Siralama Turu',
                'type' => 'select',
                'column' => 'order_by',
                'operator' => '',
                'options' => [
                    'products.id' => 'ID',
                    'products.name' => 'Urun Adi',
                    'products_main.category_id' => 'Kategori',
                    'products_main.brand_id' => 'Marka',
                    'products_main.type_id' => 'Urun Turu',
                    'products.status' => 'Durum',
                ],
            ],
            'order_direction' => [
                'label' => 'Siralama Yonu',
                'type' => 'select',
                'column' => 'order_direction',
                'operator' => '',
                'options' => [
                    'asc' => 'A-Z',
                    'desc' => 'Z-A',
                ],
            ],
        ];
    }

    public function prepareDataRequest(): self
    {
        $data = request()->only('name', 'type', 'value', 'start_date', 'end_date', 'minimum_spend', 'status'); // ! status cikacak
        $data['status'] = request()->has('status');

        $this->prepareData = $data;

        return $this;
    }

    public function setPrepareData(array $data): self
    {
        $this->prepareData = $data;
        return $this;
    }

    public function create(): Discounts
    {
        return $this->discount::create($this->prepareData);
    }

    public function getDiscounts(int $perPage = 0)
    {
        $query = $this->discount::query();
        $filters = $this->getFilters();
        $query = $this->filterService->applyFilters($query, $filters);

        if ($perPage) return $this->filterService->paginate($query, $perPage);

        return $query->get();
    }

    public function getById(int $id): Discounts|ModelNotFoundException
    {
        return $this->discount::findOrFail($id);
    }

    public function setDiscount(Discounts $discounts): self
    {
        $this->discount = $discounts;
        return $this;
    }

    public function update(array $data = null): bool
    {
        if (is_null($data)) $data = $this->prepareData;

        return $this->discount->update($data);
    }

    public function delete(): bool
    {
        return $this->discount->delete();
    }

    public function assignProductsProcess(array $productIds): bool
    {
        $oldAssignProducts = $this->getAssignProducts()->pluck('id')->toArray();

        $newProductIds = $this->diffNewOldAssignIds($productIds, $oldAssignProducts);

        if (count($newProductIds)) {
            $this->assignProducts($newProductIds);
            return true;
        }

        return false;
    }

    public function assignProducts(array $productIds): self
    {
        $this->discount->products()->attach($productIds);

        return $this;
    }

    public function getAssignProducts(): Collection
    {
        return $this->discount->products;
    }



    public function assignCategoryProcess(array $categoryIds): bool
    {
        $oldAssignCategories = $this->getAssignCategories()->pluck('id')->toArray();

        $newCategoryIds = $this->diffNewOldAssignIds($categoryIds, $oldAssignCategories);

        if (count($newCategoryIds)) {
            $this->assignCategories($newCategoryIds);
            return true;
        }

        return false;
    }

    public function getAssignCategories(): Collection
    {
        return $this->discount->categories;
    }

    public function assignCategories(array $categoryIds): self
    {
        $this->discount->categories()->attach($categoryIds);

        return $this;
    }

    public function assignBrandProcess(array $brandIds): bool
    {
        $oldAssignBrands = $this->getAssignBrands()->pluck('id')->toArray();

        $newBrandIds = $this->diffNewOldAssignIds($brandIds, $oldAssignBrands);

        if (count($newBrandIds)) {
            $this->assignBrands($newBrandIds);
            return true;
        }

        return false;
    }

    public function getAssignBrands(): Collection
    {
        return $this->discount->brands;
    }

    public function assignBrands(array $brandIds): self
    {
        $this->discount->brands()->attach($brandIds);

        return $this;
    }

    public function diffNewOldAssignIds(array $newIDs, array $oldIDs): array
    {
        return array_diff($newIDs, $oldIDs);
    }

    public function getDiscountForProductList()
    {

        $categoryId = request()->input('category_id');
        $brandId = request()->input('brand_id');
        $typeId = request()->input('type_id');
        $gender = request()->input('gender');
        $name = request()->input('product_name');
        $finalPriceMin = request()->input('final_price_min');
        $finalPriceMax = request()->input('final_price_max');
        $status = request()->input('status');
        $orderBy = request()->input('order_by');
        $orderDirection = request()->input('order_direction');

        $query = Discounts::query()
            ->join('discount_products', 'discount_products.discount_id', '=', 'discounts.id')
            ->join('products', 'products.id', '=', 'discount_products.product_id')
            ->join('products_main', 'products_main.id', '=', 'products.main_product_id')
            ->join('discount_categories', 'discount_categories.discount_id', '=', 'discounts.id')
            ->join('categories', 'categories.id', '=', 'discount_categories.category_id')
            ->join('discount_brands', 'discount_brands.discount_id', '=', 'discounts.id')
            ->join('brands', 'brands.id', '=', 'discount_brands.brand_id')
            ->select('discounts.*', 'products.id as pId', 'products.name as pName', 'products.final_price', 'products.status', 'products_main.category_id')
            // ->groupBy('discounts.*', 'products.id', 'products.name', 'products.final_price', 'products.status', 'products_main.category_id');
        // ->distinct();


        if (!is_null($categoryId) && $categoryId != 'all') {
            $query->where('products_main.category_id', $categoryId);
        }
        if (!is_null($brandId) && $brandId != 'all') {
            $query->where('products_main.brand_id', $brandId);
        }
        if (!is_null($typeId) && $typeId != 'all') {
            $query->where('products_main.type_id', $typeId);
        }
        if (!is_null($gender) && $gender != 'all') {
            $query->where('products_main.gender', $gender);
        }
        if (!is_null($status) && $status != 'all') {
            $query->where('products.status', $status);
        }
        if (!is_null($name)) {
            $query->where('products.name', 'LIKE', "%$name%");
        }
        if (!is_null($finalPriceMin)) {
            $query->where('products.final_price', '>=', number_format((float)$finalPriceMin, 2, thousands_separator: ''));
        }
        if (!is_null($finalPriceMax)) {
            $query->where('products.final_price', '<=', number_format((float)$finalPriceMax, 2, thousands_separator: ''));
        }

        $query = $query->where('discounts.id', request()->discount->id);

        if (!is_null($orderBy) && !is_null($orderDirection)) {
            $query->orderBy($orderBy, $orderDirection);
        } else {
            $query->orderBy('discounts.id', 'DESC');
        }

        return $query->get();
    }
}