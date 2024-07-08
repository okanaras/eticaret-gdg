<?php

namespace App\Http\Controllers\Admin;

use App\Enums\GenderEnum;
use App\Traits\GdgException;
use Exception;
use App\Models\Product;
use Illuminate\Support\Str;
use App\Models\ProductsMain;
use App\Models\ProductTypes;
use Illuminate\Http\Request;
use App\Services\BrandService;
use App\Services\ProductService;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use Throwable;

class ProductController extends Controller
{
    use GdgException;

    public function __construct(public BrandService $brandService, public CategoryService $categoryService, public ProductService $productService)
    {
    }
    public function index()
    {
        $filters = $this->productService->getFilters();

        return view('admin.product.index')->with('filters', $filters);
    }

    public function search(Request $request)
    {
        $products = $this->productService->getProducts(1);
        return $products;
    }

    public function create()
    {
        $categories = $this->categoryService->getAllCategories();
        $brands = $this->brandService->getAll();
        $types = ProductTypes::all();
        $genders = GenderEnum::cases();

        return view('admin.product.create_edit', compact('categories', 'brands', 'types', 'genders'));
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
            toast($exception->getMessage(), 'Error');
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
        $genders = GenderEnum::cases();


        $product = $productsMain->load([
            'variants',
            'variants.variantImages',
            'variants.sizeStock',
        ])->toArray();

        return view('admin.product.create_edit', compact('product', 'categories', 'brands', 'types', 'genders'));
    }

    public function update(ProductUpdateRequest $request, ProductsMain $productsMain)
    {
        try {
            dd($request->all());
            $this->productService->update($request, $productsMain);

            toast('Urun guncellendi', 'success');
            return redirect()->route('admin.product.index');
        } catch (Throwable $exception) {
            return $this->exception($exception, 'admin.product.index', 'Urun guncellenemedi');
        }
    }

    public function delete(ProductsMain $productsMain)
    {
        try {
            $this->productService->productMainService->setProductMain($productsMain)->delete();

            toast('Urun silindi', 'success');
            return redirect()->back();
        } catch (Throwable $exception) {
            return $this->exception($exception, 'admin.product.index', 'Urun silinemedi');
        }
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

    public function changeStatus(Request $request): JsonResponse
    {
        $id = $request->id;

        $productMain = $this->productService->productMainService->getById($id);

        if (is_null($productMain)) {
            return response()
                ->json()
                ->setData([
                    'message' => 'Urun bulunamadi.'
                ])
                ->setStatusCode(404)
                ->setCharset('utf-8')
                ->header('Content-Type', 'application.json')
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }

        $data = ['status' => !$productMain->status];
        $this->productService
            ->productMainService
            ->setProductMain($productMain)
            ->setPrepareData($data)
            ->update();

        return response()
            ->json()
            ->setData($productMain)
            ->setStatusCode(200)
            ->setCharset('utf-8')
            ->header('Content-Type', 'application.json')
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}