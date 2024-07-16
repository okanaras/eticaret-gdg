<?php

namespace App\Http\Controllers\Admin;

use App\Enums\DiscountTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\DiscountStoreRequest;
use App\Services\DiscountService;
use Illuminate\Http\Request;
use Throwable;

class DiscountController extends Controller
{
    public function __construct(public DiscountService $discountService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $discounts = $this->discountService->getDiscounts(10);
        $filters = $this->discountService->getFilters();

        return view("admin.discount.index", compact("filters", "discounts"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $types = DiscountTypeEnum::cases();
        return view("admin.discount.create_edit", compact("types"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DiscountStoreRequest $request)
    {
        // try {
        $this->discountService->prepareDataRequest()->create();
        toast('Indirim kaydedildi.', 'success');
        return to_route('admin.discount.index');
        // }
        //  catch (Throwable $th) {
        //     return $this->exception($th, 'admin.category.index', 'Kategori eklenmedi.');
        // }
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}