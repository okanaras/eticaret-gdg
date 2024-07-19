<?php

namespace App\Http\Controllers\Admin;

use Throwable;
use App\Traits\GdgException;
use Illuminate\Http\Request;
use App\Services\DiscountService;
use App\Http\Controllers\Controller;
use App\Services\DiscountCouponService;
use App\Http\Requests\DiscountCouponStoreRequest;
use App\Http\Requests\DiscountCouponUpdateRequest;
use App\Models\DiscountCoupons;

class DiscountCouponsController extends Controller
{
    use GdgException;

    public function __construct(public DiscountCouponService $discountCouponService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filters = $this->discountCouponService->getFilters();
        $coupons = $this->discountCouponService->getCoupons(10);

        return view("admin.discount.coupon.index", compact("filters", "coupons"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(DiscountService $discountService)
    {
        $discounts = $discountService->getDiscounts();

        return view("admin.discount.coupon.create_edit", compact("discounts"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DiscountCouponStoreRequest $request)
    {
        try {
            $this->discountCouponService->prepareDataRequest()->create();

            toast('Indirim kodu kaydedildi.', 'success');
            return redirect()->route('admin.discount-coupons.index');
        } catch (Throwable $th) {
            return $this->exception($th, 'admin.discount-coupons.index', 'Indirim kodu eklenemedi.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id, DiscountService $discountService)
    {
        $discounts = $discountService->getDiscounts();
        $discount = $this->discountCouponService->getById($id);

        return view('admin.discount.coupon.create_edit', compact('discount', 'discounts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DiscountCouponUpdateRequest $request, string $id)
    {
        try {
            $discountCoupons = $this->discountCouponService->getById($id);
            if ($discountCoupons) {
                $this->discountCouponService->setDiscountCoupon($discountCoupons)
                    ->prepareDataRequest()
                    ->update();

                toast('Indirim kodu guncellendi.', 'success');
                return redirect()->route('admin.discount-coupons.index');
            }

            toast('Indirim kodu bulunamadi ve guncellenemedi.', 'info');
            return redirect()->route('admin.discount-coupons.index');
        } catch (Throwable $th) {
            return $this->exception($th, 'admin.discount-coupons.index', 'Indirim kodu guncellenemedi.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $discountCoupons = $this->discountCouponService->getById($id);
            if ($discountCoupons) {
                $this->discountCouponService->setDiscountCoupon($discountCoupons)
                    ->delete();

                toast('Indirim kodu silindi.', 'success');
                return redirect()->route('admin.discount-coupons.index');
            }

            toast('Indirim kodu bulunamadi ve silinemedi.', 'info');
            return redirect()->route('admin.discount-coupons.index');
        } catch (Throwable $th) {
            return $this->exception($th, 'admin.discount-coupons.index', 'Indirim kodu silinemedi.');
        }
    }

    public function restore(Request $request)
    {
        try {
            $discountCouponID = $request->discount_coupon;
            $discountCoupon = $this->discountCouponService->getByIdWT($discountCouponID);

            if ($discountCoupon) {
                $this->discountCouponService->setDiscountCoupon($discountCoupon)->restore();
                toast('Indirimi kodu geri getirildi.', 'success');
                return redirect()->back();
            }
            toast('Indirimi kodu bulunumadi ve geri getirilemedi.', 'error');
            return redirect()->back();
        } catch (Throwable $th) {
            return $this->exception($th, 'admin.discount.index', 'Indirim kodu geri getirilemedi!');
        }
    }
}