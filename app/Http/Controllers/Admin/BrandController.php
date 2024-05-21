<?php

namespace App\Http\Controllers\Admin;

use App\Traits\GdgException;
use Throwable;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\BrandService;
use App\Services\ImageService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\BrandStoreRequest;

class BrandController extends Controller
{
    use GdgException;
    public function __construct(public BrandService $brandService)
    {
    }

    public function index()
    {
        return view('admin.brand.index');
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
}