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
    public function __construct(public Brand $brand, public ImageService $imageService)
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
        $path = $this->pathEditor($this->brand->logo);

        if (file_exists(storage_path('app/' . $path))) {
            $this->imageService->deleteImage($path);
        }
    }

    public function pathEditor(string $path): string
    {
        $path = str_replace('storage', '', $path);

        return $path = 'public' . $path;
    }
}