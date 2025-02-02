@extends('layouts.admin')


@section('title', 'Slider ' . (isset($slider) ? 'Guncelleme' : 'Ekleme'))


@push('css')
    <link rel="stylesheet" href="{{ asset('assets/vendors/pickr/themes/classic.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/flatpickr/flatpickr.min.css') }}">
@endpush

@section('body')
    <div class="card">
        <div class="card-body">

            <h6 class="card-title">Slider {{ isset($slider) ? 'Guncelleme' : 'Ekleme' }}</h6>

            @php
                $curenntRoute = !isset($slider)
                    ? route('admin.slider.create')
                    : route('admin.slider.edit', $slider->id);
            @endphp

            <form class="forms-sample" action="{{ $curenntRoute }}" method="POST" id="gdgForm"
                enctype="multipart/form-data">
                @csrf
                @isset($slider)
                    @method('PUT')
                @endisset
                <div class="row">

                    <div class="mb-3">
                        <input type="checkbox" class="form-check-input" id="status" name="status" value="1"
                            {{ isset($slider) ? ($slider->status ? 'checked' : '') : (old('status') ? 'checked' : '') }}>
                        <label class="form-check-label" for="status">
                            Aktif mi?
                        </label>
                        @error('status')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Slider Adi</label>
                        <input type="text" class="form-control" id="name" placeholder="Slider Adi" name="name"
                            value="{{ isset($slider) ? $slider->name : old('name') }}">
                        @error('name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="order" class="form-label">Sira Numarasi</label>
                        <input type="number" class="form-control" id="order" placeholder="Sira Numarasi" name="order"
                            value="{{ isset($slider) ? $slider->order : old('order') }}">
                        @error('order')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    @php
                        $logoStatus = isset($slider) && file_exists($slider->path);
                    @endphp

                    <div class="col-md-12 mb-3">
                        <div class="row">
                            <div @class([
                                'col-md-6' => $logoStatus,
                                'col-md-12' => !$logoStatus,
                            ])>
                                <label for="path" class="form-label">Slider Gorseli</label>
                                <input type="file" class="form-control" id="path" name="path">
                                <small class="text-warning">En fazla 2mb buyuklugunde logo yukleyebilirsiniz.</small>
                                @if (!$logoStatus && isset($slider))
                                    <img class="d-block" src="{{ asset('assets/images/logo-placeholder.png') }}"
                                        height="200">
                                    <small class="text-info d-block">Daha once logo eklenmemistir.</small>
                                @endif
                                @error('path')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            @if ($logoStatus)
                                <div class="col-md-6">
                                    <img src="{{ asset($slider->path) }}" class="img-fluid" style="max-height: 200px"
                                        alt="{{ $slider->name }}">
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="release_start" class="form-label">Yayimlanma Tarihi</label>

                        <div class="input-group flatpickr flatpickr-date">
                            <input type="text" class="form-control flatpickr-input active" name="release_start"
                                id="release_start" placeholder="Yayimlanma Tarihi" value="" data-input="">
                            <span class="input-group-text input-group-addon" data-toggle=""><i
                                    data-feather="calendar"></i></span>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="release_finish" class="form-label">Bitis Tarihi</label>

                        <div class="input-group flatpickr flatpickr-date">
                            <input type="text" class="form-control flatpickr-input active" name="release_finish"
                                id="release_finish" placeholder="Bitis Tarihi" value="" data-input="">
                            <span class="input-group-text input-group-addon" data-toggle=""><i
                                    data-feather="calendar"></i></span>
                        </div>
                    </div>


                    {{-- 1 text, color ve css --}}
                    <div class="col-md-12 mb-3">
                        <label for="row_1_text" class="form-label">1. Satir Yazisi</label>
                        <input type="text" class="form-control" id="row_1_text" autocomplete="off"
                            placeholder="1. Satir Yazisi" name="row_1_text"
                            value="{{ isset($slider) ? $slider->row_1_text : old('row_1_text') }}">
                        @error('row_1_text')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="row_1_css" class="form-label">1. Satir Yazi CSS</label>
                        <input type="hidden" name="row_1_css">
                        <textarea id="row_1_css" class="ace-editor w-100"></textarea>

                        @error('row_1_css')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="row_1_color_button" class="form-label">1. Satir Yazi Renk Kodu</label>
                        <div id="row_1_color_button"></div>
                        <input type="hidden" name="row_1_color" id="row_1_color" value="">

                        @error('row_1_color')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- 2 text, color ve css --}}
                    <div class="col-md-12 mb-3">
                        <label for="row_2_text" class="form-label">2. Satir Yazisi</label>
                        <input type="text" class="form-control" id="row_2_text" autocomplete="off"
                            placeholder="2. Satir Yazisi" name="row_2_text"
                            value="{{ isset($slider) ? $slider->row_2_text : old('row_2_text') }}">
                        @error('row_2_text')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="row_2_css" class="form-label">2. Satir Yazi CSS</label>
                        <input type="hidden" name="row_2_css">
                        <textarea id="row_2_css" class="ace-editor w-200"></textarea>

                        @error('row_2_css')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="row_2_color_button" class="form-label">2. Satir Yazi Renk Kodu</label>
                        <div id="row_2_color_button"></div>
                        <input type="hidden" name="row_2_color" id="row-2-color" value="">

                        @error('row_2_color')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- 3button text, color ve css --}}
                    <div class="col-md-4 mb-3">
                        <label for="button_text" class="form-label">Button Yazisi</label>
                        <input type="text" class="form-control" id="button_text" autocomplete="off"
                            placeholder="Button Yazisi" name="button_text"
                            value="{{ isset($slider) ? $slider->button_text : old('button_text') }}">
                        @error('button_text')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="button_url" class="form-label">Button URL(Link)</label>
                        <input type="text" class="form-control" id="button_url" autocomplete="off"
                            placeholder="Button URL (Link)" name="button_url"
                            value="{{ isset($slider) ? $slider->button_url : old('button_url') }}">
                        @error('button_url')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="button_target" class="form-label">Button Yazi Renk Kodu</label>
                        <select name="button_target" id="button_target" class="form-select">
                            <option value="self">Ayni Sekmede</option>
                            <option value="_blank">Farkli Sekmede</option>
                        </select>

                        @error('button_target')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="button_css" class="form-label">Button Yazi CSS</label>
                        <input type="hidden" name="button_css">

                        <textarea id="button_css" class="ace-editor w-200"></textarea>

                        @error('button_css')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="button_text_color_button" class="form-label">Button Yazi Renk Kodu</label>
                        <div id="button_text_color_button"></div>
                        <input type="hidden" name="button_color" id="button_color" value="">

                        @error('button_color')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>


                    <button type="button" class="btn btn-primary me-2" id="btnSubmit">Kaydet</button>
                </div>

            </form>

        </div>
    </div>
@endsection


@push('js')
    <script src="{{ asset('assets/vendors/pickr/pickr.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/ace-builds/src-min/ace.js') }}"></script>
    <script src="{{ asset('assets/vendors/ace-builds/src-min/theme-chaos.js') }}"></script>
    <script src="{{ asset('assets/vendors/flatpickr/flatpickr.min.js') }}"></script>


    <script>
        document.addEventListener('DOMContentLoaded', () => {

            // color picker - editor start

            let row1ColorInput = document.querySelector('#row_1_color');
            let row2ColorInput = document.querySelector('#row_2_color');
            let buttonColorInput = document.querySelector('#button_color');

            let row1CssInput = document.querySelector('[name="row_1_css"]');
            let row2CssInput = document.querySelector('[name="row_2_css"]');
            let buttonCssInput = document.querySelector('[name="button_css"]');


            // row 1
            const row1ColorButton = Pickr.create({
                el: '#row_1_color_button',
                theme: 'classic', // or 'monolith', or 'nano',
                default: '{{ isset($slider) ? $slider->row_1_color : '' }}',

                swatches: [
                    'rgba(244, 67, 54, 1)',
                    'rgba(233, 30, 99, 0.95)',
                    'rgba(156, 39, 176, 0.9)',
                    'rgba(103, 58, 183, 0.85)',
                    'rgba(63, 81, 181, 0.8)',
                    'rgba(33, 150, 243, 0.75)',
                    'rgba(3, 169, 244, 0.7)',
                    'rgba(0, 188, 212, 0.7)',
                    'rgba(0, 150, 136, 0.75)',
                    'rgba(76, 175, 80, 0.8)',
                    'rgba(139, 195, 74, 0.85)',
                    'rgba(205, 220, 57, 0.9)',
                    'rgba(255, 235, 59, 0.95)',
                    'rgba(255, 193, 7, 1)'
                ],

                components: {

                    // Main components
                    preview: true,
                    opacity: true,
                    hue: true,

                    // Input / output Options
                    interaction: {
                        hex: true,
                        rgba: true,
                        hsla: true,
                        hsva: true,
                        cmyk: true,
                        input: true,
                        clear: true,
                        save: true
                    }
                }
            });

            row1ColorButton.on('save', (color, instance) => {
                row1ColorInput.value = color.toHEXA().toString();
            });

            var row_1_css_editor = ace.edit("row_1_css");
            row_1_css_editor.setTheme("ace/theme/dracula");
            row_1_css_editor.getSession().setMode("ace/mode/scss");
            row_1_css_editor.setOption("showPrintMargin", false)


            // row 2
            const row2ColorButton = Pickr.create({
                el: '#row_2_color_button',
                theme: 'classic', // or 'monolith', or 'nano',
                default: '{{ isset($slider) ? $slider->row_2_color : '' }}',

                swatches: [
                    'rgba(244, 67, 54, 1)',
                    'rgba(233, 30, 99, 0.95)',
                    'rgba(156, 39, 176, 0.9)',
                    'rgba(103, 58, 183, 0.85)',
                    'rgba(63, 81, 181, 0.8)',
                    'rgba(33, 150, 243, 0.75)',
                    'rgba(3, 169, 244, 0.7)',
                    'rgba(0, 188, 212, 0.7)',
                    'rgba(0, 150, 136, 0.75)',
                    'rgba(76, 175, 80, 0.8)',
                    'rgba(139, 195, 74, 0.85)',
                    'rgba(205, 220, 57, 0.9)',
                    'rgba(255, 235, 59, 0.95)',
                    'rgba(255, 193, 7, 1)'
                ],

                components: {

                    // Main components
                    preview: true,
                    opacity: true,
                    hue: true,

                    // Input / output Options
                    interaction: {
                        hex: true,
                        rgba: true,
                        hsla: true,
                        hsva: true,
                        cmyk: true,
                        input: true,
                        clear: true,
                        save: true
                    }
                }
            });

            row2ColorButton.on('save', (color, instance) => {
                row2ColorInput.value = color.toHEXA().toString();
            });

            var row_2_css_editor = ace.edit("row_2_css");
            row_2_css_editor.setTheme("ace/theme/dracula");
            row_2_css_editor.getSession().setMode("ace/mode/scss");
            row_2_css_editor.setOption("showPrintMargin", false)

            // button
            const buttonColorButton = Pickr.create({
                el: '#button_text_color_button',
                theme: 'classic', // or 'monolith', or 'nano',
                default: '{{ isset($slider) ? $slider->button__color : '' }}',

                swatches: [
                    'rgba(244, 67, 54, 1)',
                    'rgba(233, 30, 99, 0.95)',
                    'rgba(156, 39, 176, 0.9)',
                    'rgba(103, 58, 183, 0.85)',
                    'rgba(63, 81, 181, 0.8)',
                    'rgba(33, 150, 243, 0.75)',
                    'rgba(3, 169, 244, 0.7)',
                    'rgba(0, 188, 212, 0.7)',
                    'rgba(0, 150, 136, 0.75)',
                    'rgba(76, 175, 80, 0.8)',
                    'rgba(139, 195, 74, 0.85)',
                    'rgba(205, 220, 57, 0.9)',
                    'rgba(255, 235, 59, 0.95)',
                    'rgba(255, 193, 7, 1)'
                ],

                components: {

                    // Main components
                    preview: true,
                    opacity: true,
                    hue: true,

                    // Input / output Options
                    interaction: {
                        hex: true,
                        rgba: true,
                        hsla: true,
                        hsva: true,
                        cmyk: true,
                        input: true,
                        clear: true,
                        save: true
                    }
                }
            });

            buttonColorButton.on('save', (color, instance) => {
                buttonColorInput.value = color.toHEXA().toString();
            });

            var button_css_editor = ace.edit("button_css");
            button_css_editor.setTheme("ace/theme/dracula");
            button_css_editor.getSession().setMode("ace/mode/scss");
            button_css_editor.setOption("showPrintMargin", false)

            @isset($slider)
                row_1_css_editor.setValue('{{ $slider->row_1_css }}');
                row_2_css_editor.setValue('{{ $slider->row_2_css }}');
                button_css_editor.setValue('{{ $slider->button_css }}');
            @endisset


            // color picker - editor end

            flatpickr(".flatpickr-date", {
                wrap: true,
                enableTime: false,
                dateFormat: "Y-m-d",
            });

            let btnSubmit = document.querySelector('#btnSubmit');
            let gdgForm = document.querySelector('#gdgForm');
            let path = document.querySelector('#path');
            let name = document.querySelector('#name');

            btnSubmit.addEventListener('click', () => {
                row1CssInput.value = row_1_css_editor.getValue();
                row2CssInput.value = row_2_css_editor.getValue();
                buttonCssInput.value = button_css_editor.getValue();

                let images = path.files;

                if (!name.value.length) {
                    toastr.warning('Slider icin ad vermelisiniz!', 'Uyari!');
                } else if (!images.length && !gdgForm.getAttribute('action').includes('edit')) {
                    toastr.warning('Slider icin gorsel secmediniz!', 'Uyari!');
                } else if (images.length) {
                    let image = images[0];
                    let validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                    let maxSize = 2 * 1024 * 1024;

                    if (!validTypes.includes(image.type)) {
                        toastr.warning('Gorseliniz jpg, jpeg, png ya da webp turlerinde olmalidir!',
                            'Uyari!');
                    } else if (image.size > maxSize) {
                        toastr.warning('Gorsel en fazla 2MB buyuklugunde olmalidir.!', 'Uyari!');
                    } else {
                        gdgForm.submit();
                    }
                } else {
                    gdgForm.submit();
                }
            });
        });
    </script>
@endpush
