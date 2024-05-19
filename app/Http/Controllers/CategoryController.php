<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryStoreRequest;
use App\Http\Requests\CategoryUpdateRequest;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::orderBy('id', 'DESC')->paginate(10);

        return view('admin.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        // $categories = Category::query()->whereNull('parent_id')->get(); // sadece ana kategorileri getirir
        return view('admin.category.create_edit', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategoryStoreRequest $request)
    {
        $data = $request->only('name', 'short_description', 'description'); // 3'unu array seklinde rq ten aldik slug icin ayri islem yapacagiz

        $slug = Str::slug($request->slug);

        if (is_null($request->slug)) {
            # eger rq ten gelen slug null ise rq teki name in mbsubstr ile ilk 64 char ini slugladik

            $slug = Str::slug(mb_substr($data['name'], 0, 64));

            $checkSlug = Category::query()->where('slug', $slug)->first();

            // slug imiz egerki db de daha once varsa hata mesaji ve girilen input degerleriyle geri donduruyoruz.
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

        Category::create($data);

        toast('Kategori kaydedildi.', 'success');
        return redirect()->route('admin.category.index');
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
}