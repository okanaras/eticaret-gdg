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
                'table' => 'products',
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
                    'products.final_price' => 'Urun Fiyati',
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

    public function getFiltersForCategories(): array
    {
        $categories = Category::all()->pluck('name', 'id')->toArray();
        $categories = ['all' => 'Tumu'] + $categories;

        return [
            'name' => [
                'label' => 'Kategori Adi',
                'type' => 'text',
                'column_live' => 'name',
                'table' => 'categories',
                'column' => 'name',
                'operator' => 'like',
            ],
            'parent_id' => [
                'label' => 'Ust Kategori',
                'type' => 'select',
                'column_live' => 'parent_id',
                'table' => 'categories',
                'column' => 'parent_id',
                'operator' => '=',
                'options' => $categories,
            ],
            'status' => [
                'label' => 'Durum',
                'type' => 'select',
                'column_live' => 'status',
                'table' => 'categories',
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
                    'categories.id' => 'ID',
                    'categories.name' => 'Kategori Adi',
                    'categories.parent_id' => 'Ust Kategori',
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

    public function getFiltersForBrands(): array
    {
        return [

            'name' => [
                'label' => 'Marka Adi',
                'type' => 'text',
                'column' => 'name',
                'column_live' => 'name',
                'table' => 'brands',
                'operator' => 'like',
            ],

            'status' => [
                'label' => 'Durum',
                'type' => 'select',
                'column' => 'status',
                'column_live' => 'status',
                'table' => 'brands',
                'operator' => '=',
                'options' => ['all' => 'Tumu', 'Pasif', 'Aktif'],
            ],
            'is_featured' => [
                'label' => 'One Cikarilma Durumu',
                'type' => 'select',
                'column' => 'is_featured',
                'column_live' => 'is_featured',
                'table' => 'brands',
                'operator' => '=',
                'options' => ['all' => 'Tumu', 'Hayir', 'Evet'],
            ],
            'order_by' => [
                'label' => 'Siralama Turu',
                'type' => 'select',
                'column' => 'order_by',
                'operator' => '',
                'options' => [
                    'brands.id' => 'ID',
                    'brands.name' => 'Marka Adi',
                    'brands.status' => 'Durum',
                    'brands.is_featured' => 'One Cikarilma Durumu',
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
        $query = Discounts::query()
            ->join('discount_products', 'discount_products.discount_id', '=', 'discounts.id')
            ->join('products', 'products.id', '=', 'discount_products.product_id')
            ->join('products_main', 'products_main.id', '=', 'products.main_product_id')
            ->join('categories', 'categories.id', '=', 'products_main.category_id')
            ->join('brands', 'brands.id', '=', 'products_main.brand_id')
            ->join('product_types', 'product_types.id', '=', 'products_main.type_id')
            ->select('discounts.*', 'products.id as pId', 'products.name as pName', 'products.final_price', 'products.status', 'products_main.category_id', 'categories.name as cName', 'brands.name as bName', 'product_types.name as ptName')
            ->where('discounts.id', request()->discount->id);

        $filters = $this->getFiltersForProduct();
        $query = $this->filterService->applyFilters($query, $filters);

        return $this->filterService->paginate($query, 10);
    }

    public function getDiscountForCategoryList()
    {
        $query = Discounts::query()
            ->join('discount_categories', 'discount_categories.discount_id', '=', 'discounts.id')
            ->join('categories', 'categories.id', '=', 'discount_categories.category_id')
            ->leftjoin('categories as parentCategory', 'parentCategory.id', '=', 'categories.parent_id')
            ->select('discounts.*', 'categories.id as cId', 'categories.name as cName', 'categories.parent_id', 'parentCategory.name as parentCategoryName')
            ->where('discounts.id', request()->discount->id);

        $filters = $this->getFiltersForCategories();
        $query = $this->filterService->applyFilters($query, $filters);

        return $this->filterService->paginate($query, 10);
    }

    public function getDiscountForBrandList()
    {
        $query = Discounts::query()
            ->join('discount_brands', 'discount_brands.discount_id', '=', 'discounts.id')
            ->join('brands', 'brands.id', '=', 'discount_brands.brand_id')
            ->select('discounts.*', 'brands.id as bId', 'brands.name as bName', 'brands.logo')
            ->where('discounts.id', request()->discount->id);


        $filters = $this->getFiltersForBrands();
        $query = $this->filterService->applyFilters($query, $filters);

        return $this->filterService->paginate($query, 10);
    }
}