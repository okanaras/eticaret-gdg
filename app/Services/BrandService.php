<?php

namespace App\Services;

use Exception;
use App\Models\Brand;
use Illuminate\Support\Str;

class BrandService
{
    private array $prepareData = [];
    public function __construct(public Brand $brand, public ImageService $imageService)
    {
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
        $data['logo'] = $logoPath;

        $slug = $this->slugGenerate($data['name'], request()->slug);

        $data['slug'] = $slug;
        $data['status'] = request()->has('status');
        $data['is_feature'] = request()->has('is_feature');

        $this->prepareData = $data;
        return $this;
    }

    public function uploadLogo(string $fileName): string|null
    {
        if (request()->has('logo')) {
            $logo = request()->file('logo');
            $path = 'public/uploads/brands/original';

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
}