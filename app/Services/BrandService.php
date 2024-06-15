<?php

namespace App\Services;

use Exception;
use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;

class BrandService
{
    private array $prepareData = [];

    public function __construct(public Brand $brand, public ImageService $imageService, public FilterService $filterService)
    {
    }

    public function getById(int $id): Brand|null
    {
        return $this->brand::query()->where('id', $id)->first();
    }

    public function getAllPaginate(int $page = 10, $orderBy = ['order', 'DESC']): LengthAwarePaginator
    {
        return $this->brand::orderBy($orderBy[0], $orderBy[1])->paginate($page);
    }

    public function getAll($orderBy = ['order', 'DESC'])
    {
        return $this->brand::orderBy($orderBy[0], $orderBy[1])->get();
    }

    public function getBrands(int $perPage = 0)
    {
        $query = $this->brand::query();
        $filters = $this->getFilters();
        $query = $this->filterService->applyFilters($query, $filters);
        if ($perPage) {
            return $this->filterService->paginate($query, $perPage);
        }
        return $query->get();
    }

    public function getFilters(): array
    {
        return [
            'order' => [
                'label' => 'Sira Numarasi',
                'type' => 'number',
                'column' => 'order',
                'operator' => '=',
            ],
            'name' => [
                'label' => 'Marka Adi',
                'type' => 'text',
                'column' => 'name',
                'operator' => 'like',
            ],
            'slug' => [
                'label' => 'Slug',
                'type' => 'text',
                'column' => 'slug',
                'operator' => 'like',
            ],
            'status' => [
                'label' => 'Durum',
                'type' => 'select',
                'column' => 'status',
                'operator' => '=',
                'options' => ['all' => 'Tumu', 'Pasif', 'Aktif'],
            ],
            'is_featured' => [
                'label' => 'One Cikarilma Durumu',
                'type' => 'select',
                'column' => 'is_featured',
                'operator' => '=',
                'options' => ['all' => 'Tumu', 'Hayir', 'Evet'],
            ],
            'order_by' => [
                'label' => 'Siralama Turu',
                'type' => 'select',
                'column' => 'order_by',
                'operator' => '',
                'options' => [
                    'id' => 'ID',
                    'order' => 'Sira Numarasi',
                    'name' => 'Marka Adi',
                    'status' => 'Durum',
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

    public function create(array $data = null)
    {
        if (is_null($data)) {
            $data = $this->prepareData;
        }

        return $this->brand->create($data);
    }

    public function prepareDataRequest(): self
    {
        $data = request()->only('name', 'order');

        $logoPath = $this->uploadLogo($data['name']);
        if (!is_null($logoPath) || (is_null($logoPath) && is_null($this->brand->logo))) {
            $this->deleteLogo();
            $data['logo'] = $logoPath;
        }
        $slug = $this->slugGenerate($data['name'], request()->slug);

        $data['slug'] = $slug;
        $data['status'] = request()->has('status');
        $data['is_feature'] = request()->has('is_feature');

        $this->prepareData = $data;
        return $this;
    }

    public function setPrepareData(array $data): self
    {
        $this->prepareData = $data;
        return $this;
    }

    public function uploadLogo(string $fileName): string|null
    {
        if (request()->has('logo')) {
            $logo = request()->file('logo');
            $path = 'uploads/brands/original';

            $fileName = str_replace('storage', '', $fileName);

            return $this->imageService->singleUpload($logo, $fileName, $path);
        }

        return null;
    }

    public function checkSlug(string $slug): Brand|null
    {
        return $this->brand->query()->where('slug', $slug)->first();
    }

    public function slugGenerate(string $name, string|null $slug): string
    {
        if (is_null($slug)) {

            $slug = Str::slug(mb_substr($name, 0, 64));
            $checkSlug = $this->checkSlug($slug);

            if ($checkSlug) {
                throw new Exception('Slug degeriniz bos veya daha once farkli bir marka icin kullaniliyor olabilir!', 400);
            }
        } else {
            $slug = Str::slug($slug);
        }

        return $slug;
    }

    public function setBrand(Brand $brand): self
    {
        $this->brand = $brand;
        return $this;
    }

    public function update(array $data = null): bool
    {
        if (is_null($data)) {
            $data = $this->prepareData;
        }
        return $this->brand->update($data);
    }

    public function delete(): bool
    {
        $this->deleteLogo();
        return $this->brand->delete();
    }

    public function deleteLogo()
    {
        $logo = $this->brand->logo;
        $path = is_null($logo) ? '' :  pathEditor($logo);

        if (file_exists(storage_path('app/' . $path))) {
            $this->imageService->deleteImage($path);
        }
    }
}