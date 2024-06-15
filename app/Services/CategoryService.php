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

    public function __construct(public Category $category, public FilterService $filterService)
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

    public function getCategories(int $perPage = 0)
    {
        $query = $this->category::query()->with('parentCategory');
        $filters = $this->getFilters();
        $query = $this->filterService->applyFilters($query, $filters);
        if ($perPage) {
            return $this->filterService->paginate($query, $perPage);
        }
        return $query->get();
    }

    public function getFilters(): array
    {
        $categories = $this->category->all()->pluck('name', 'id')->toArray();
        $categories = ['all' => 'Tumu'] + $categories;

        return [
            'name' => [
                'label' => 'Kategori Adi',
                'type' => 'text',
                'column' => 'name',
                'operator' => 'like',
            ],
            'short_description' => [
                'label' => 'Kisa Aciklama',
                'type' => 'text',
                'column' => 'short_description',
                'operator' => 'like',
            ],
            'description' => [
                'label' => 'Aciklama',
                'type' => 'text',
                'column' => 'description',
                'operator' => 'like',
            ],
            'parent_id' => [
                'label' => 'Ust Kategori',
                'type' => 'select',
                'column' => 'parent_id',
                'operator' => '=',
                'options' => $categories,
            ],
            'status' => [
                'label' => 'Durum',
                'type' => 'select',
                'column' => 'status',
                'operator' => '=',
                'options' => ['all' => 'Tumu', 'Pasif', 'Aktif'],
            ],
            'order_by' => [
                'label' => 'Siralama Turu',
                'type' => 'select',
                'column' => 'order_by',
                'operator' => '',
                'options' => [
                    'id' => 'ID',
                    'name' => 'Marka Adi',
                    'status' => 'Durum',
                    'parent_id' => 'Ust Kategori',
                    'is_featured' => 'One Cikarilma Durumu',
                ],
            ],
            'order_direction' => [
                'label' => 'Siralama Yonu',
                'type' => 'select',
                'column' => 'order_direction',
                'operator' => '',
                'options' => [
                    'asc' => 'A-Z',
                    'desc' => 'Z-A',
                ],
            ],
        ];
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