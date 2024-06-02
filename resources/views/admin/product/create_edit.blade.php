@extends('layouts.admin')


@section('title', 'Urun Ekleme')


@push('css')
    <link rel="stylesheet" href="{{ asset('assets/vendors/flatpickr/flatpickr.min.css') }}">
@endpush


@section('body')
    <div class="card">
        <div class="card-body">

            <h6 class="card-title">Urun Ekleme</h6>
            <form class="forms-sample" action="" method="POST" id="gdgForm" enctype="multipart/form-data">
                @csrf
                @isset($brand)
                    @method('PUT')
                @endisset
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#product-info" role="tab"
                            aria-controls="home" aria-selected="true">Urun Bilgileri</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="productVariantTab" data-bs-toggle="tab" href="#product-variant"
                            role="tab" aria-controls="product-variant" aria-selected="false" disabled="">Urun Varyant
                            Ekleme</a>
                    </li>
                </ul>
                <div class="tab-content border border-top-0 p-3" id="myTabContent">
                    <div class="tab-pane fade show active" id="product-info" role="tabpanel"
                        aria-labelledby="product-info-tab">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="name" class="form-label">Urun Adi</label>
                                <input type="text" class="form-control" id="name" autocomplete="off"
                                    placeholder="Urun Adi" name="name" value="{{ old('name') }}">
                                @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="price" class="form-label">Fiyat</label>
                                <input type="text" class="form-control" id="price" placeholder="Fiyat" name="price"
                                    value="{{ old('price') }}">
                                @error('price')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-4">
                                <label for="type_id" class="form-label">Urun Turu</label>
                                <select class="form-select" name="type_id" id="type_id">
                                    <option selected='selected' value="-1">Urun Turu Seciniz</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type->id }}">
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-4">
                                <label for="brand_id" class="form-label">Marka</label>
                                <select class="form-select" name="brand_id" id="brand_id">
                                    <option selected='selected' value="-1">Marka Seciniz</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}">
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('brand_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-4">
                                <label for="category_id" class="form-label">Kategori</label>
                                <select class="form-select" name="category_id" id="category_id">
                                    <option selected='selected' value="-1">Kategori Seciniz</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="short_description" class="form-label">Kisa Aciklama</label>
                                <textarea class="form-control" name="short_description" id="short_description" rows="7">
                                    {{ old('short_description') }}
                                </textarea>

                                @error('short_description')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="description" class="form-label">Aciklama</label>
                                <textarea class="form-control" name="description" id="description" rows="3">
                                    {{ old('description') }}
                                </textarea>

                                @error('description')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-check col-md-4 mb-4">
                                <input type="checkbox" class="form-check-input" id="status" name="status"
                                    {{ old('status') ? 'checked' : '' }}>
                                <label class="form-check-label" for="status">
                                    Aktif mi?
                                </label>
                            </div>

                        </div>
                    </div>
                    <div class="tab-pane fade" id="product-variant" role="tabpanel"
                        aria-labelledby="product-variant-tab">
                        <a href="javascript:void(0)" id="addVariant">
                            <div>
                                <i data-feather="plus-square"></i>
                                <span class="ms-2">Varyant Ekle</span>
                            </div>
                        </a>
                        <hr class="my-3">
                        <div id="variants"></div>
                    </div>
                </div>

                <button type="button" class="btn btn-primary me-2 mt-5" id="btnSubmit">Kaydet</button>
            </form>

        </div>
    </div>
@endsection


@push('js')
    <script src="{{ asset('assets/vendors/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/js/product/gdg-variant.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let btnSubmit = document.querySelector('#btnSubmit');
            let gdgForm = document.querySelector('#gdgForm');
            let name = document.querySelector('#name');

            btnSubmit.addEventListener('click', () => {
                if (name.value.trim().length < 1) {
                    toastr.warning('Lutfen Urun adini yaziniz!',
                        'Uyari!');
                } else {
                    gdgForm.submit();
                }
            });


            if ($('.flatpickr-date').length) {
                flatpickr(".flatpickr-date", {
                    wrap: true,
                    enableTime: true,
                    dateFormat: "Y-m-d H:i",
                });
            }
        });
    </script>
@endpush
