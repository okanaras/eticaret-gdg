<?php

namespace App\Http\Controllers\Front;

use App\Enums\GenderEnum;
use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductController extends Controller
{
    public function __construct(public ProductService $productService)
    {
    }

    public function list(Request $request, CategoryService $categoryService)
    {
        $selectedValues = [];
        foreach ($request->all() as $itemKey => $itemValue) {
            $selectedValues[$itemKey] = explode(',', $itemValue);
        }

        $categories = $categoryService->getAllActiveCategories();
        $genders = GenderEnum::cases();

        $products = $this->productService->getSearchProducts($request, $selectedValues);

        // if ($selectedValues) {
        //     $perPage = 1;
        //     $currentPage = LengthAwarePaginator::resolveCurrentPage();
        //     $currentPageProducts = $products->slice(($currentPage - 1) * $perPage, $perPage)->all();
        //     $paginateProducts = new LengthAwarePaginator(
        //         $currentPageProducts,
        //         $products->count(),
        //         $perPage,
        //         $currentPage,
        //         ['path' => LengthAwarePaginator::resolveCurrentPath()]
        //     );
        //     $products = $paginateProducts;
        //     // dd($products);
        // } else {
        //     $products = $this->productService->getAllActiveProducts();
        // }

        // dd('$products: ', $products);

        return view('front.product-list', compact('categories', 'genders', 'products', 'selectedValues'));
    }
    public function detail()
    {
        return view('front.product-detail');
    }
}