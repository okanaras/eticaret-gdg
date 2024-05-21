<?php

namespace App\Http\Controllers\Admin;

use App\Traits\GdgException;
use Error;
use Exception;
use Throwable;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\CategoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;

class CategoryController extends Controller
{
    use GdgException;

    public function __construct(public CategoryService $categoryService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $categories = Category::orderBy('id', 'DESC')->paginate(10);

        $categories = $this->categoryService->getAllCategoriesPaginate(orderBy: ['name', 'ASC']);

        /**
         * constructeer olusturmadan tek seferlik asagidaki gibi service i kullanabiliriz
         *
         * $categoryService = new CategoryService(new Category());
         * $categoryService = App::make(CategoryService::class);
         */
        return view('admin.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $categories = Category::query()->whereNull('parent_id')->get(); // sadece ana kategorileri getirir

        // $categories = Category::all();
        $categories = $this->categoryService->getAllCategories();

        return view('admin.category.create_edit', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryStoreRequest $request)
    {
        try {
            $this->categoryService->prepareDataRequest()->create();

            toast('Kategori kaydedildi.', 'success');
            return redirect()->route('admin.category.index');
        } catch (Throwable $th) {
            return $this->exception($th, 'admin.category.index', 'Kategori eklenmedi.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $categories = $this->categoryService->getAllCategories();
        return view('admin.category.create_edit', compact('category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryUpdateRequest $request, Category $category)
    {

        try {
            $this->categoryService->setCategory($category)->prepareDataRequest()->update();

            toast('Kategori guncellendi.', 'success');
            return redirect()->route('admin.category.index');
        } catch (Throwable $th) {
            return $this->exception($th, 'admin.category.index', 'Kategori guncellenemedi.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            $this->categoryService->setCategory($category)->delete();

            toast('Kategori silindi.', 'success');
            return redirect()->back();
        } catch (Throwable $th) {
            return $this->exception($th, 'admin.category.index', 'Kategori silinemedi');
        }
    }

    public function front()
    {
        // ana kategori olup alt kategorileri olan sorgu
        $categories = Category::query()
            ->with('children')
            ->whereHas('children')
            ->whereNull('parent_id')
            ->get();

        return view('categories', compact('categories'));
    }

    public function changeStatus(Request $request): JsonResponse
    {
        $id = $request->id;

        $category = $this->categoryService->getById($id);

        if (is_null($category)) {
            return response()
                ->json()
                ->setData([
                    'message' => 'Kategori bulunamadi.'
                ])
                ->setStatusCode(404)
                ->setCharset('utf-8')
                ->header('Content-Type', 'application.json')
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }

        $data = ['status' => !$category->status];
        $this->categoryService
            ->setCategory($category)
            ->setPrepareData($data)
            ->update();

        return response()
            ->json()
            ->setData($category)
            ->setStatusCode(200)
            ->setCharset('utf-8')
            ->header('Content-Type', 'application.json')
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}