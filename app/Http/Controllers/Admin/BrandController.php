<?php

namespace App\Http\Controllers\Admin;

use Throwable;
use App\Models\Brand;
use Illuminate\Support\Str;
use App\Traits\GdgException;
use Illuminate\Http\Request;
use App\Services\BrandService;
use App\Services\ImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\BrandStoreRequest;
use App\Http\Requests\BrandUpdateRequest;

class BrandController extends Controller
{
    use GdgException;
    public function __construct(public BrandService $brandService)
    {
    }

    public function index()
    {
        $brands = $this->brandService->getAllPaginate();
        return view('admin.brand.index', compact('brands'));
    }

    public function create()
    {

        return view('admin.brand.create_edit');
    }

    public function store(BrandStoreRequest $request)
    {
        try {
            $this->brandService->prepareDataRequest()->create();
            toast('Marka kaydedildi.', 'success');

            return redirect()->route('admin.brand.index');
        } catch (Throwable $th) {
            return $this->exception($th, 'admin.brand.index', 'Marka eklenemedi.');
        }
    }

    public function edit(Brand $brand)
    {
        return view('admin.brand.create_edit', compact('brand'));
    }

    public function update(BrandUpdateRequest $request, Brand $brand)
    {
        try {
            $this->brandService->setBrand($brand)->prepareDataRequest()->update();

            toast('Marka guncellendi.', 'success');
            return redirect()->route('admin.brand.index');
        } catch (Throwable $th) {
            return $this->exception($th, 'admin.brand.index', 'Marka guncellenemedi.');
        }
    }

    public function delete(Brand $brand)
    {
        try {
            $this->brandService->setBrand($brand)->delete();

            toast('Marka silindi.', 'success');
            return redirect()->back();
        } catch (Throwable $th) {
            return $this->exception($th, 'admin.brand.index', 'Marka silinemedi');
        }
    }

    public function changeStatus(Request $request): JsonResponse
    {
        $id = $request->id;

        $brand = $this->brandService->getById($id);

        if (is_null($brand)) {
            return response()
                ->json()
                ->setData([
                    'message' => 'Marka bulunamadi.'
                ])
                ->setStatusCode(404)
                ->setCharset('utf-8')
                ->header('Content-Type', 'application.json')
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }

        $data = ['status' => !$brand->status];
        $this->brandService
            ->setBrand($brand)
            ->setPrepareData($data)
            ->update();

        return response()
            ->json()
            ->setData($brand)
            ->setStatusCode(200)
            ->setCharset('utf-8')
            ->header('Content-Type', 'application.json')
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    public function changeIsFeatured(Request $request): JsonResponse
    {
        $id = $request->id;

        $brand = $this->brandService->getById($id);

        if (is_null($brand)) {
            return response()
                ->json()
                ->setData([
                    'message' => 'Marka bulunamadi.'
                ])
                ->setStatusCode(404)
                ->setCharset('utf-8')
                ->header('Content-Type', 'application.json')
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }

        $data = ['is_featured' => !$brand->is_featured];
        $this->brandService
            ->setBrand($brand)
            ->setPrepareData($data)
            ->update();

        return response()
            ->json()
            ->setData($brand)
            ->setStatusCode(200)
            ->setCharset('utf-8')
            ->header('Content-Type', 'application.json')
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}