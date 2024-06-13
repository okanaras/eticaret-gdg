<?php

namespace App\Http\Controllers\Admin;

use Throwable;
use App\Traits\GdgException;
use Illuminate\Http\Request;
use App\Services\SliderService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Sliders;

class SlidersController extends Controller
{
    use GdgException;

    public function __construct(public SliderService $sliderService)
    {
    }

    public function index()
    {
        $sliders = $this->sliderService->getAllPaginate();

        return view('admin.slider.index', compact('sliders'));
    }
    public function create()
    {
        return view('admin.slider.create_edit');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'path' => ['required', 'image', 'max:2048', 'mimes:jpg,jpeg,webp,png'],
            'order' => ['nullable', 'sometimes', 'integer']
        ]);

        try {
            $this->sliderService->prepareData($request->all())->store();
            toast('Slider kaydedildi.', 'success');

            return redirect()->route('admin.slider.index');
        } catch (Throwable $th) {
            return $this->exception($th, 'admin.slider.index', 'Slider eklenemedi.');
        }
    }

    public function edit(Sliders $slider)
    {
        return view('admin.slider.create_edit', compact('slider'));
    }

    public function update(Request $request, Sliders $slider)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'path' => ['nullable', 'sometimes', 'image', 'max:2048', 'mimes:jpg,jpeg,webp,png'],
            'order' => ['nullable', 'sometimes', 'integer']
        ]);

        try {
            $this->sliderService
                ->setSlider($slider)
                ->prepareData($request->all())
                ->update();

            toast('Slider guncellendi.', 'success');
            return redirect()->route('admin.slider.index');
        } catch (Throwable $th) {
            dd($th->getMessage());
            return $this->exception($th, 'admin.slider.index', 'Slider guncellenemedi.');
        }
    }

    public function delete(Sliders $slider)
    {
        try {
            $this->sliderService->setSlider($slider)->delete();

            toast('Slider silindi.', 'success');
            return redirect()->back();
        } catch (Throwable $th) {
            return $this->exception($th, 'admin.slider.index', 'Slider silinemedi');
        }
    }


    public function changeStatus(Request $request): JsonResponse
    {
        $id = $request->id;

        $slider = $this->sliderService->getById($id);

        if (is_null($slider)) {
            return response()
                ->json()
                ->setData([
                    'message' => 'Slider bulunamadi.'
                ])
                ->setStatusCode(404)
                ->setCharset('utf-8')
                ->header('Content-Type', 'application.json')
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        }

        $data = ['status' => !$slider->status];
        $this->sliderService
            ->setSlider($slider)
            ->setPrepareData($data)
            ->update();

        return response()
            ->json()
            ->setData($slider)
            ->setStatusCode(200)
            ->setCharset('utf-8')
            ->header('Content-Type', 'application.json')
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}