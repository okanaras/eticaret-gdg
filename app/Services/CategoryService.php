<?php

namespace App\Services;

use Exception;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CategoryService
{
    private array $prepareData = [];

    public function __construct(public Category $category)
    {
    }

    public function setCategory(Category $category): self
    {
        $this->category = $category;
        return $this;
    }

    public function getAllCategories(): Collection
    {
        return $this->category::all();
    }

    public function getAllCategoriesPaginate(int $page = 10, $orderBy = ['id', 'DESC']): LengthAwarePaginator
    {
        return $this->category::orderBy($orderBy[0], $orderBy[1])->paginate($page);
    }

    public function prepareDataRequest(): self
    {
        $data = request()->only('name', 'short_description', 'description');

        $slug = $this->slugGenerate($data['name'], request()->slug);

        $data['slug'] = $slug;
        $data['status'] = request()->has('status');

        if (request()->parent_id != -1) {

            $data['parent_id'] = request()->parent_id;
        }

        $this->prepareData = $data;
        return $this;
    }

    public function setPrepareData(array $data): self
    {
        $this->prepareData = $data;
        return $this;
    }

    public function create(array $data = null): Category
    {
        if (is_null($data)) {
            $data = $this->prepareData;
        }
        return $this->category->create($data);
    }

    public function update(array $data = null): bool
    {
        if (is_null($data)) {
            $data = $this->prepareData;
        }
        return $this->category->update($data);
    }

    public function delete(): bool
    {
        return $this->category->delete();
    }

    public function checkSlug(string $slug): Category|null
    {
        return $this->category->query()->where('slug', $slug)->first();
    }

    public function slugGenerate(string $name, string|null $slug): string
    {
        if (is_null($slug)) {
            # eger rq ten gelen slug null ise rq teki name in mbsubstr ile ilk 64 char ini slugladik

            $slug = Str::slug(mb_substr($name, 0, 64));
            $checkSlug = $this->checkSlug($slug);

            // slug imiz egerki db de daha once varsa hata mesaji ve girilen input degerleriyle geri donduruyoruz.
            if ($checkSlug) {
                throw new Exception('Slug degeriniz bos veya daha once farkli bir kategori icin kullaniliyor olabilir!', 400);
            }
        } else {
            $slug = Str::slug($slug);
        }

        return $slug;
    }

    public function getById(int $id): Category|null
    {
        return $this->category::query()->where('id', $id)->first();
    }
}