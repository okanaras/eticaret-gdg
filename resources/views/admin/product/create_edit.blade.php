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
                        <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#product-variant" role="tab"
                            aria-controls="product-variant" aria-selected="false">Urun Varyant Ekleme</a>
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
                                <label for="tpye_id" class="form-label">Urun Turu</label>
                                <select class="form-select" name="tpye_id" id="tpye_id">
                                    <option selected='selected' value="-1">Urun Turu Seciniz</option>
                                    @foreach ($types as $type)
                                        <option value="{{ $type->id }}">
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('tpye_id')
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
                                <label for="category_id" class="form-label">Kategory</label>
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
                        <div>
                            <i data-feather="plus-square"></i>
                            <span class="ms-2">Varyant Ekle</span>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <label for="name" class="form-label">Urun Adi</label>
                                <input type="text" class="form-control" id="name" autocomplete="off"
                                    placeholder="Urun Adi" name="name" value="{{ old('name') }}">

                            </div>

                            <div class="col-md-4 mb-4">
                                <label for="variant_name" class="form-label">Urun Varyant Adi</label>
                                <input type="text" class="form-control" id="variant_name" autocomplete="off"
                                    placeholder="Urun Adi" name="variant_name" value="{{ old('variant_name') }}">

                            </div>

                            <div class="col-md-4 mb-4">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" class="form-control" id="slug" placeholder="Slug"
                                    name="slug" value="{{ old('slug') }}">

                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="additional_price" class="form-label">Fiyat</label>
                                <input type="text" class="form-control" id="additional_price" placeholder="Fiyat"
                                    name="additional_price" value="{{ old('additional_price') }}">

                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="final_price" class="form-label">Son Fiyat</label>
                                <input type="text" class="form-control" id="final_price" placeholder="Son Fiyat"
                                    name="final_price" value="{{ old('final_price') }}">

                            </div>

                            <div class="col-md-12 mb-4">
                                <label for="extra_description" class="form-label">Ekstra Aciklama</label>
                                <textarea class="form-control" name="extra_description" id="extra_description" rows="7">
                                    {{ old('extra_description') }}
                                </textarea>


                            </div>

                            <div class="col-md-12 mb-4">
                                <label for="publish_date" class="form-label">Yayimlanma Tarihi</label>

                                <div class="input-group flatpickr" id="flatpickr-date">
                                    <input type="text" name="publish_date" id="publish_date" class="form-control"
                                        placeholder="Yayimlanma tarihi seciniz" data-input>
                                    <span class="input-group-text input-group-addon" data-toggle><i
                                            data-feather="calendar"></i></span>
                                </div>

                            </div>

                            <div class="form-check col-md-6 mb-4">
                                <input type="checkbox" class="form-check-input" id="p_status" name="p_status"
                                    {{ old('p_status') ? 'checked' : '' }}>
                                <label class="form-check-label" for="p_status">
                                    Aktif mi?
                                </label>

                            </div>

                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-primary me-2 mt-5" id="btnSubmit">Kaydet</button>
            </form>

        </div>
    </div>
@endsection


@push('js')
    <script src="{{ asset('assets/vendors/flatpickr/flatpickr.min.js') }}"></script>

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


            if ($('#flatpickr-date').length) {
                flatpickr("#flatpickr-date", {
                    wrap: true,
                    enableTime: true,
                    dateFormat: "Y-m-d H:i",
                });
            }
        });
    </script>
@endpush
