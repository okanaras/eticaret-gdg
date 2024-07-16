<?php

namespace App\Services;

use App\Enums\DiscountTypeEnum;
use App\Models\Discounts;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;

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
}