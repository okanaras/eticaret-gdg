<?php

namespace App\Http\Controllers\Front;

use App\Enums\GenderEnum;
use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(public ProductService $productService)
    {
    }

    public function list(Request $request, CategoryService $categoryService)
    {
        $categories = $categoryService->getAllActiveCategories();
        $genders = GenderEnum::cases();
        $products = $this->productService->getAllActiveProducts();

        // dd('$request: ', $request);

        return view('front.product-list', compact('categories', 'genders', 'products'));
    }
    public function detail()
    {
        return view('front.product-detail');
    }
}