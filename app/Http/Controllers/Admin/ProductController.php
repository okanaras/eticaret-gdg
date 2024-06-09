<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProductStoreRequest;
use App\Models\Product;
use App\Models\ProductImages;
use Illuminate\Support\Str;
use App\Models\ProductTypes;
use Illuminate\Http\Request;
use App\Services\BrandService;
use App\Services\CategoryService;
use App\Http\Controllers\Controller;
use App\Models\ProductsMain;
use App\Models\SizeStock;
use Carbon\Carbon;

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
        $validated = $request->validated();
        // dd($validated);

        $productsMain = ProductsMain::create([
            'category_id' => $validated['category_id'],
            'brand_id' => $validated['brand_id'],
            'type_id' => $validated['type_id'],
            'name' => $validated['name'],
            'price' => $validated['price'],
            'short_description' => $validated['short_description'],
            'description' => $validated['description'],
            'status' => isset($validated['status']) ? 1 : 0,
        ]);



        foreach ($validated['variant'] as $variant) {
            $product =  Product::create([
                'main_product_id' => $productsMain->id,
                'name' => $variant['name'],
                'variant_name' => $variant['variant_name'],
                'slug' => Str::slug($variant['slug']),
                'additional_price' => $variant['additional_price'],
                'final_price' => number_format(($validated['price'] + $variant['additional_price']), 2),
                'extra_description' => $variant['extra_description'],
                'status' => isset($variant['status']) ? 1 : 0,
                'publish_date' => isset($variant['publish_date']) ? Carbon::parse($variant['publish_date'])->toDateTimeString() : null
            ]);



            $images = explode(',', $variant['image']);
            foreach ($images as $image) {
                ProductImages::create([
                    'product_id' => $product->id,
                    'path' => $image,
                    'is_featured' => ($variant['featured_image'] == $image) ? 1 : 0,
                ]);
            }


            foreach ($variant['size'] as $index => $size) {
                SizeStock::create([
                    'product_id' => $product->id,
                    'size' => $size,
                    'stock' => $variant['stock'][$index],
                    'remaining_stock' => $variant['stock'][$index],
                ]);
            }
        }

        dd('islem tamam');
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