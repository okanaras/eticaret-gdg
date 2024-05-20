<?php

namespace App\Http\Controllers;

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
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;

class CategoryController extends Controller
{
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
            $this->categoryService->prepareDataForCreate213()->create();

            toast('Kategori kaydedildi.', 'success');
            return redirect()->route('admin.category.index');
        } catch (Throwable $th) {
            if ($th->getCode() == 400) {
                return redirect()
                    ->back()
                    ->withErrors([
                        'slug' => $th->getMessage()
                    ])->withInput();
            }

            toast('Kategori kaydedilmedi.', 'error');

            Log::error('Store Methodu Category Alinan Hata: ' . $th->getMessage(), [$th->getTraceAsString()]);
            return redirect()->route('admin.category.index');
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
        $categories = Category::all();
        return view('admin.category.create_edit', compact('category', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategoryUpdateRequest $request, Category $category)
    {
        $data = $request->only('name', 'short_description', 'description');

        $slug = Str::slug($request->slug);

        if (is_null($request->slug)) {

            $slug = Str::slug(mb_substr($data['name'], 0, 64));

            $checkSlug = Category::query()->where('slug', $slug)->first();

            if ($checkSlug) {
                return redirect()
                    ->back()
                    ->withErrors([
                        'slug' => 'Slug degeriniz bos veya daha once farkli bir kategori icin kullaniliyor olabilir!'
                    ])->withInput();
            }
        }

        $data['slug'] = $slug;
        $data['status'] = $request->has('status');

        if ($request->parent_id != -1) {

            $data['parent_id'] = $request->parent_id;
        }

        $category->update($data);

        toast('Kategori guncellendi.', 'success');
        return redirect()->route('admin.category.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();

        toast('Kategori silindi.', 'success');
        return redirect()->back();
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

        $category = Category::query()->where('id', $id)->first();

        // dd($category);

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

        $category->status = !$category->status;
        $category->save();

        return response()
            ->json()
            ->setData($category)
            ->setStatusCode(200)
            ->setCharset('utf-8')
            ->header('Content-Type', 'application.json')
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}