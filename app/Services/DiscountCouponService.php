<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Discounts;
use App\Models\DiscountCoupons;

class DiscountCouponService
{
    private array $prepareData = [];

    public function __construct(public DiscountCoupons $discountCoupons, public FilterService $filterService)
    {
    }

    public function prepareDataRequest(): self
    {
        $data = request()->only('code', 'discount_id', 'usage_limit', 'expiry_date');

        $this->prepareData = $data;
        return $this;
    }

    public function create(array $data = null): DiscountCoupons
    {
        if (is_null($data)) {
            $data = $this->prepareData;
        }

        return $this->discountCoupons::create($data);
    }

    public function update(array $data = null): bool
    {
        if (is_null($data)) {
            $data = $this->prepareData;
        }

        if ($usedCount = $this->discountCoupons->used_count) {
            unset($data['discount_id']);
            if ($usedCount > $data['usage_limit']) {
                $data['usage_limit'] = $usedCount;
            }
        }

        if (now() > Carbon::parse($data['expiry_date']) && $this->discountCoupons->expiry_date != $data['expiry_date']) {
            $data['expiry_date'] = now()->subDay()->format('Y-m-d');
        }

        return $this->discountCoupons->update($data);
    }

    public function delete(): bool
    {
        return $this->discountCoupons->delete();
    }

    public function restore(): bool
    {
        return $this->discountCoupons->restore();
    }

    public function getFilters(): array
    {
        $discounts = Discounts::all()->pluck('name', 'id')->toArray();
        $discounts = ['all' => 'Tumu'] + $discounts;

        return [
            'code' => [
                'label' => 'Indirim Kodu',
                'type' => 'text',
                'column' => 'code',
                'operator' => 'like',
            ],
            'dicount_id' => [
                'label' => 'Indirim Tanimlamasi',
                'type' => 'select',
                'column' => 'discount_id',
                'operator' => '=',
                'options' => $discounts,
            ],
            'with_trashed' => [
                'label' => 'Silinmis Veriler Getirilsin Mi?',
                'type' => 'select',
                'column' => 'with_trashed',
                'column_live' => 'deleted_at',
                'table' => 'discount_coupons',
                'operator' => '=',
                'options' => ['Hayir', 'Evet'],
            ],
            'usage_limit_min' => [
                'label' => 'Kullanim Limiti(min)',
                'type' => 'number',
                'column' => 'usage_limit_min',
                'column_live' => 'usage_limit',
                'table' => 'discount_coupons',
                'operator' => '>=',
            ],
            'usage_limit_max' => [
                'label' => 'Kullanim Limiti(max)',
                'type' => 'number',
                'column' => 'usage_limit_max',
                'column_live' => 'usage_limit',
                'table' => 'discount_coupons',
                'operator' => '<=',
            ],
            'order_by' => [
                'label' => 'Siralama Turu',
                'type' => 'select',
                'column' => 'order_by',
                'operator' => '',
                'options' => [
                    'id' => 'ID',
                    'code' => 'Code',
                    'dicount_id' => 'Indirim Tanimlamasi',
                    'usage_limit' => 'Maksimim Kullanim Degeri',
                    'used_count' => 'Kullanim Miktari',
                    'expiry_date' => 'Son Kullanim Tarihi',
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

    public function getCoupons(int $perPage = 0)
    {
        $query = $this->discountCoupons::query();
        $filters = $this->getFilters();
        $query = $this->filterService->applyFilters($query, $filters);
        if ($perPage) {
            return $this->filterService->paginate($query, $perPage);
        }
        return $query->get();
    }

    public function getById(int $id): ?DiscountCoupons
    {
        return $this->discountCoupons->find($id);
    }

    public function getByIdWT(int $id): ?DiscountCoupons
    {
        return $this->discountCoupons::query()
            ->withTrashed()
            ->find($id);
    }

    public function setDiscountCoupon(DiscountCoupons $discountCoupon): self
    {
        $this->discountCoupons = $discountCoupon;
        return $this;
    }
}