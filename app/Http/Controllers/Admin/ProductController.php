<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProductStoreRequest;
use App\Models\Product;
use Illuminate\Support\Str;
use App\Models\ProductTypes;
use Illuminate\Http\Request;
use App\Services\BrandService;
use App\Services\CategoryService;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function __construct(public BrandService $brandService, public CategoryService $categoryService)
    {
    }
    public function index()
    {
        return view('admin.product.index');
    }

    public function create()
    {
        $categories = $this->categoryService->getAllCategories();
        $brands = $this->brandService->getAll();

        $types = ProductTypes::all();

        return view('admin.product.create_edit', compact('categories', 'brands', 'types'));
    }

    public function store(ProductStoreRequest $request)
    {
    }

    public function checkSlug(Request $request)
    {
        $check = Product::query()->where('slug', Str::slug($request->slug))->first();

        return response()
            ->json()
            ->setData($check)
            ->setStatusCode(200)
            ->setCharset('utf-8')
            ->header('Content-Type', 'application.json')
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}