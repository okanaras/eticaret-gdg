<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Product;
use Illuminate\Support\Str;
use App\Models\ProductsMain;
use App\Models\ProductTypes;
use Illuminate\Http\Request;
use App\Services\BrandService;
use App\Services\ProductService;
use App\Services\CategoryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;

class ProductController extends Controller
{
    public function __construct(public BrandService $brandService, public CategoryService $categoryService, public ProductService $productService)
    {
    }
    public function index()
    {
        $productsMain = ProductsMain::all();

        return view('admin.product.index')->with('products', $productsMain);
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
        DB::beginTransaction();
        try {
            $this->productService->store($request);
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();

            Log::error('Alinan Hata: ' . $exception->getMessage(), [$exception->getTraceAsString()]);
            // toast($exception->getMessage(), 'Error');
            return redirect()->back()->withInput();
        }

        toast('Urun kaydedildi.', 'success');
        return redirect()->route('admin.product.index');
    }

    public function edit(Request $request, ProductsMain $productsMain)
    {
        $categories = $this->categoryService->getAllCategories();
        $brands = $this->brandService->getAll();

        $types = ProductTypes::all();

        $product = $productsMain->load([
            'variants',
            'variants.variantImages',
            'variants.sizeStock',
        ])->toArray();

        // dd($product);
        // dd($productsMain->toArray());

        return view('admin.product.create_edit', compact('product', 'categories', 'brands', 'types'));
    }

    public function update(ProductUpdateRequest $request)
    {
        dd($request->all());
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