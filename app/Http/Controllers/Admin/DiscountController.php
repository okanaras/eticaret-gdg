<?php

namespace App\Http\Controllers\Admin;

use App\Enums\DiscountTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\DiscountAssignProductsRequest;
use App\Http\Requests\DiscountStoreRequest;
use App\Models\Discounts;
use App\Models\Product;
use App\Services\BrandService;
use App\Services\CategoryService;
use App\Services\DiscountService;
use App\Services\ProductServices\ProductService;
use App\Traits\GdgException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class DiscountController extends Controller
{
    use GdgException;
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
        try {
            $this->discountService->prepareDataRequest()->create();

            toast('Indirim kaydedildi.', 'success');
            return to_route('admin.discount.index');
        } catch (Throwable $th) {
            return $this->exception($th, 'admin.discount.index', 'Indirim eklenmedi.');
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
    public function edit(string $id)
    {
        $discount = $this->discountService->getById($id);
        $types = DiscountTypeEnum::cases();

        return view('admin.discount.create_edit', compact('discount', 'types'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DiscountStoreRequest $request, string $id)
    {


        try {
            $discount = $this->discountService->getById($id);
            $this->discountService->setDiscount($discount)->prepareDataRequest()->update();

            toast('Indirim guncellendi.', 'success');
            // return redirect()->route('admin.discount.index');
            return to_route('admin.discount.index');
        } catch (Throwable $th) {
            return $this->exception($th, 'admin.discount.index', 'Indirim guncellenemedi.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $discount = $this->discountService->getById($id);
            $this->discountService->setDiscount($discount)->delete();

            toast('Indirim silindi.', 'success');
            return redirect()->back();
        } catch (Throwable $th) {
            return $this->exception($th, 'admin.discount.index', 'Indirim silinemedi');
        }
    }

    public function changeStatus(Request $request): JsonResponse
    {
        try {
            $id = $request->id;

            $discount = $this->discountService->getById($id);

            $data = ['status' => !$discount->status];
            $this->discountService
                ->setDiscount($discount)
                ->setPrepareData($data)
                ->update();

            return response()
                ->json()
                ->setData($discount)
                ->setStatusCode(200)
                ->setCharset('utf-8')
                ->header('Content-Type', 'application.json')
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        } catch (ModelNotFoundException $th) {
            return $this->jsonException($th, ['message' => 'Indirim bulunamadi'], 404);
        } catch (Throwable $th) {
            return $this->jsonException($th, ['message' => 'Indirim bulunamadi']);
        }
    }

    public function showAssignProductsForm(Discounts $discount, ProductService $productService)
    {
        $products = $productService->getAllActive();
        $data = (object)[
            'items' => $products,
            'title' => 'Urune Indirim Ekleme',
            'label' => 'Indirim Yapilcak Urun',
            'select_id' => 'product_ids',
            'select_name' => 'product_ids',
            'option' => 'Indirim Yapilcak Urunu Seciniz',
            'route' => route('admin.discount.assign-products', $discount->id),
            'message' => 'Lutfen indirim yapilacak urunu seciniz!',
        ];

        return view('admin.discount.assign-product.assign', compact('discount', 'data'));
    }

    public function showAssignProducts(DiscountAssignProductsRequest $request, Discounts $discount)
    {
        try {
            $result = $this->discountService->setDiscount($discount)->assignProductsProcess($request->product_ids);

            if ($result) {
                toast('Atama yapildi.', 'success');
                return redirect()->back();
            } else {
                toast('Atama yapilmadi. Daha onceden urune ayni indirim eklenmistir.', 'error');
                return redirect()->back();
            }
        } catch (Throwable $th) {
            dd($th->getMessage());
        }
    }

    public function showAssignCategoriesForm(Discounts $discount, CategoryService $categoryService)
    {
        $categories = $categoryService->getAllActiveCategories();
        $data = (object)[
            'items' => $categories,
            'title' => 'Kategoriye Indirim Ekleme',
            'label' => 'Indirim Yapilcak Kategori',
            'select_id' => 'category_ids',
            'select_name' => 'category_ids',
            'option' => 'Indirim Yapilcak Kategoriye Seciniz',
            'route' => route('admin.discount.assign-categories', $discount->id),
            'message' => 'Lutfen indirim yapilacak kategoriyi seciniz!',
        ];

        return view('admin.discount.assign-product.assign', compact('discount', 'data'));
    }

    public function showAssignCategories(Request $request, Discounts $discount)
    {
        $request->validate([
            'category_ids' => ['required', 'array'],
            'category_ids.*' => ['exists:categories,id'],
        ]);

        try {
            $result = $this->discountService->setDiscount($discount)->assignCategoryProcess($request->category_ids);

            if ($result) {
                toast('Atama yapildi.', 'success');
                return redirect()->back();
            } else {
                toast('Atama yapilmadi. Daha onceden kategoriye ayni indirim eklenmistir.', 'error');
                return redirect()->back();
            }
        } catch (Throwable $th) {
            dd($th->getMessage());
        }
    }

    public function showAssignBrandsForm(Discounts $discount, BrandService $brandService)
    {
        $brands = $brandService->getAllActive();
        $data = (object)[
            'items' => $brands,
            'title' => 'Markaya Indirim Ekleme',
            'label' => 'Indirim Yapilcak Kategori',
            'select_id' => 'brands_ids',
            'select_name' => 'brands_ids',
            'option' => 'Indirim Yapilcak Markayi Seciniz',
            'route' => route('admin.discount.assign-brands', $discount->id),
            'message' => 'Lutfen indirim yapilacak kategoriyi seciniz!',
        ];

        return view('admin.discount.assign-product.assign', compact('discount', 'data'));
    }

    public function showAssignBrands(Request $request, Discounts $discount)
    {
        $request->validate([
            'brands_ids' => ['required', 'array'],
            'brands_ids.*' => ['exists:brands,id'],
        ]);

        try {
            $result = $this->discountService->setDiscount($discount)->assignBrandProcess($request->brands_ids);

            if ($result) {
                toast('Atama yapildi.', 'success');
                return redirect()->back();
            } else {
                toast('Atama yapilmadi. Daha onceden markaya ayni indirim eklenmistir.', 'error');
                return redirect()->back();
            }
        } catch (Throwable $th) {
            dd($th->getMessage());
        }
    }

    public function showProductsList(Request $request, Discounts $discount)
    {
        $filters = $this->discountService->getFiltersForProduct();
        $discounts = $this->discountService->getDiscountForProductList();
        // dd('$discount: ', $discounts);

        return view('admin.discount.assign-product.product-list', compact('discount', 'discounts', 'filters'));
    }
}