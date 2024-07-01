<?php

namespace App\Services;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Str;
use App\Models\ProductsMain;
use App\Models\ProductTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Services\ProductServices\SizeStockService;
use App\Services\ProductServices\ProductMainService;
use App\Services\ProductServices\ProductImageService;
use App\Services\ProductServices\ProductService as PService;
use Illuminate\Support\Collection;

class ProductService
{
    public function __construct(
        public ProductMainService $productMainService,
        public PService $productService,
        public ProductImageService $productImageService,
        public SizeStockService $sizeStockService,
        public FilterService $filterService
    ) {
    }

    public function getProducts(int $perPage = 0)
    {
        $filters = $this->getFilters();
        $query = $this->productMainService->getProductsQuery();
        $query = $this->filterService->applyFilters($query, $filters);
        // dd($query->toSql());
        if ($perPage) {
            return $this->filterService->paginate($query, $perPage);
        }
        return $query->get();
    }

    public function getFilters(): array
    {
        $categories = Category::all()->pluck('name', 'id')->toArray();
        $categories = ['all' => 'Tumu'] + $categories;

        $brands = Brand::all()->pluck('name', 'id')->toArray();
        $brands = ['all' => 'Tumu'] + $brands;

        $types = ProductTypes::all()->pluck('name', 'id')->toArray();
        $types = ['all' => 'Tumu'] + $types;

        return [
            'name' => [
                'label' => 'Urun Adi',
                'type' => 'text',
                'column' => 'name',
                'column_live' => 'name',
                'table' => 'products_main',
                'operator' => 'like',
            ],
            'type_id' => [
                'label' => 'Urun Turu',
                'type' => 'select',
                'column' => 'type_id',
                'column_live' => 'type_id',
                'table' => 'products_main',
                'operator' => '=',
                'options' => $types,
            ],
            'category_id' => [
                'label' => 'Kategori',
                'type' => 'select',
                'column' => 'category_id',
                'column_live' => 'category_id',
                'table' => 'products_main',
                'operator' => '=',
                'options' => $categories,
            ],
            'brand_id' => [
                'label' => 'Marka',
                'type' => 'select',
                'column' => 'brand_id',
                'column_live' => 'brand_id',
                'table' => 'products_main',
                'operator' => '=',
                'options' => $brands,
            ],
            'gender' => [
                'label' => 'Cinsiyet',
                'type' => 'select',
                'column' => 'gender',
                'column_live' => 'gender',
                'table' => 'products_main',
                'operator' => '=',
                'options' => ['all' => 'Tumu', 'Kadin', 'Erkek'],
            ],
            'short_description' => [
                'label' => 'Kisa Aciklama',
                'type' => 'text',
                'column' => 'short_description',
                'column_live' => 'short_description',
                'table' => 'products_main',
                'operator' => 'like',
            ],
            'description' => [
                'label' => 'Aciklama',
                'type' => 'text',
                'column' => 'description',
                'column_live' => 'description',
                'table' => 'products_main',
                'operator' => 'like',
            ],
            'status' => [
                'label' => 'Durum',
                'type' => 'select',
                'column' => 'status',
                'column_live' => 'status',
                'table' => 'products_main',
                'operator' => '=',
                'options' => ['all' => 'Tumu', 'Pasif', 'Aktif'],
            ],
            'product_name' => [
                'label' => 'Urun Adi(Varyant)',
                'type' => 'text',
                'column' => 'product_name',
                'column_live' => 'name',
                'table' => 'products',
                'operator' => 'like',
            ],
            'variant_name' => [
                'label' => 'Urun Adi',
                'type' => 'text',
                'column' => 'variant_name',
                'column_live' => 'variant_name',
                'table' => 'products',
                'operator' => 'like',
            ],
            'final_price_min' => [
                'label' => 'Fiyat(min)',
                'type' => 'number',
                'column' => 'final_price_min',
                'column_live' => 'final_price',
                'table' => 'products',
                'operator' => '>=',
            ],
            'final_price_max' => [
                'label' => 'Fiyat(max)',
                'type' => 'number',
                'column' => 'final_price_max',
                'column_live' => 'final_price',
                'table' => 'products',
                'operator' => '<=',
            ],
            'extra_description' => [
                'label' => 'Ekstra Aciklama(varyant)',
                'type' => 'text',
                'column' => 'extra_description',
                'column_live' => 'extra_description',
                'table' => 'products',
                'operator' => 'like',
            ],
            'publish_date' => [
                'label' => 'Yayimlanma Tarihi',
                'type' => 'date',
                'column' => 'publish_date',
                'column_live' => 'publish_date',
                'table' => 'products',
                'operator' => '=',
            ],
            'order_by' => [
                'label' => 'Siralama Turu',
                'type' => 'select',
                'column' => 'order_by',
                'operator' => '',
                'options' => [
                    'products_main.id' => 'ID',
                    'products_main.name' => 'Urun Adi',
                    'categories.name' => 'Kategori',
                    'brands.name' => 'Marka',
                    'product_types.name' => 'Urun Turu',
                    'products_main.status' => 'Durum',
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

    public function store(Request $request): void
    {
        $productMain = $this->storeProductMain($request);
        $this->storeVariants($request, $productMain);
    }

    public function storeProductMain(Request $request): ProductsMain
    {
        return  $this->productMainService->prepareData($request->all(), $request->status)->store();
    }

    public function storeVariants(Request $request, ProductsMain $productsMain)
    {
        $data = $request->all();
        foreach ($data['variant'] as $variant) {
            $product = $this->productService->prepareData($variant, $productsMain)->store();

            $this->storeImages($variant['image'], $variant['featured_image'], $product->id);

            $this->storeSizeStock($variant, $product->id);
        }
    }

    public function storeImages(string $images, string $featuredImagePath, int $productID): array
    {
        $images = explode(',', $images);
        unset($images[(count($images) - 1)]);

        $createdImages = [];
        foreach ($images as $image) {
            $createdImages[] =  $this->productImageService->prepareData($image, $featuredImagePath, $productID)->store();
        }

        return $createdImages;
    }

    public function updateImages(Collection $productImages, array $variant, int $productID): void
    {
        foreach ($productImages as $image) {
            $this->productImageService->setProductImage($image)->delete();
        }
        $this->storeImages($variant['image'], $variant['featured_image'], $productID);
    }

    public function storeSizeStock(array $variant, int $productID): array
    {
        $createdSizeStock = [];
        foreach ($variant['size'] as $index => $size) {
            $stock = $variant['stock'][$index];
            $createdSizeStock[] = $this->sizeStockService->prepareData($size, $stock, $productID)->store();
        }

        return $createdSizeStock;
    }

    public function updateSizeStock(array $variant)
    {
        $productID = $variant['variant_index'];
        $sizeKeys = $this->sizeStockService->getByProductIdMultiple($productID)->pluck('size')->toArray();

        $newSizeArr = [];
        foreach ($variant['size'] as $index => $size) {
            $sizeStockFind = $this->sizeStockService->getByProductIdAndSize($productID, $size);

            if ($sizeStockFind) {
                $newSizeArr[] = $sizeStockFind->size;
                $stock = $variant['stock'][$index];
                $this->sizeStockService
                    ->setSizeStock($sizeStockFind)
                    ->prepareDataForUpdate($stock, $productID)
                    ->update();
            } else {
                $stock = $variant['stock'][$index];
                $this->sizeStockService->prepareData($size, $stock, $productID)->store();
            }
        }
        $this->sizeStockService->changedKeyDelete($sizeKeys, $newSizeArr, $productID);
    }

    public function update(Request $request, ProductsMain $productsMain): void
    {
        $this->updateProductMain($request, $productsMain);
        $this->updateVariants($request, $productsMain);
    }

    public function updateProductMain(Request $request, ProductsMain $productsMain): bool
    {
        return $this->productMainService
            ->prepareData($request->all(), $request->status)
            ->setProductMain($productsMain)
            ->update();
    }

    public function updateVariants(Request $request, ProductsMain $productsMain): void
    {
        $data = $request->all();
        foreach ($data['variant'] as $variant) {
            if (isset($variant['variant_index'])) {
                $productID = $variant['variant_index'];
                $product = $this->productService->getById($productID);
                $this->productService->setProduct($product)->prepareData($variant, $productsMain)->update();

                $this->updateImages($product->variantImages, $variant, $productID);
                $this->updateSizeStock($variant);
            } else {
                $product = $this->productService->prepareData($variant, $productsMain)->store();
                $this->storeImages($variant['image'], $variant['featured_image'], $product->id);
                $this->storeSizeStock($variant, $product->id);
            }
        }
    }
}
