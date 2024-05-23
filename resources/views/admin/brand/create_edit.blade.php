@extends('layouts.admin')


@section('title', 'Marka ' . (isset($brand) ? 'Guncelleme' : 'Ekleme'))


@push('css')
@endpush


@section('body')
    <div class="card">
        <div class="card-body">

            <h6 class="card-title">Marka {{ isset($brand) ? 'Guncelleme' : 'Ekleme' }}</h6>

            @php
                $curenntRoute = !isset($brand) ? route('admin.brand.store') : route('admin.brand.update', $brand->id);
            @endphp

            <form class="forms-sample" action="{{ $curenntRoute }}" method="POST" id="gdgForm" enctype="multipart/form-data">
                @csrf
                @isset($brand)
                    @method('PUT')
                @endisset
                <div class="mb-3">
                    <label for="name" class="form-label">Marka Adi</label>
                    <input type="text" class="form-control" id="name" autocomplete="off" placeholder="Marka Adi"
                        name="name" value="{{ isset($brand) ? $brand->name : old('name') }}">
                    @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" class="form-control" id="slug" placeholder="Slug" name="slug"
                        value="{{ isset($brand) ? $brand->slug : old('slug') }}">
                    @error('slug')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="status" name="status"
                        {{ isset($brand) ? ($brand->status ? 'checked' : '') : (old('status') ? 'checked' : '') }}>
                    <label class="form-check-label" for="status">
                        Aktif mi?
                    </label>
                    @error('status')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured"
                        {{ isset($brand) ? ($brand->is_featured ? 'checked' : '') : (old('is_featured') ? 'checked' : '') }}>
                    <label class="form-check-label" for="is_featured">
                        Marka one cikarilsin mi?
                    </label>
                    @error('is_featured')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="order" class="form-label">Sira Numarasi</label>
                    <input type="number" class="form-control" id="order" placeholder="Sira Numarasi" name="order"
                        value="{{ isset($brand) ? $brand->order : old('order') }}">
                    @error('order')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                @php
                    $logoStatus = isset($brand) && file_exists($brand->logo);
                @endphp

                <div class="mb-3">
                    <div class="row">
                        <div @class([
                            'col-md-6' => $logoStatus,
                            'col-md-12' => !$logoStatus,
                        ])>
                            <label for="logo" class="form-label">Logo</label>
                            <input type="file" class="form-control" id="logo" name="logo">
                            <small class="text-warning">En fazla 2mb buyuklugunde logo yukleyebilirsiniz.</small>
                            @if (!$logoStatus && isset($brand))
                                <img class="d-block" src="{{ asset('assets/images/logo-placeholder.png') }}"
                                    height="200">
                                <small class="text-info d-block">Daha once logo eklenmemistir.</small>
                            @endif
                            @error('logo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        @if ($logoStatus)
                            <div class="col-md-6">
                                <img src="{{ asset($brand->logo) }}" class="img-fluid" style="max-height: 200px"
                                    alt="{{ $brand->name }}">
                            </div>
                        @endif
                    </div>
                </div>

                <button type="button" class="btn btn-primary me-2" id="btnSubmit">Kaydet</button>
            </form>

        </div>
    </div>
@endsection


@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let btnSubmit = document.querySelector('#btnSubmit');
            let gdgForm = document.querySelector('#gdgForm');
            let name = document.querySelector('#name');

            btnSubmit.addEventListener('click', () => {
                if (name.value.trim().length < 1) {
                    toastr.warning('Lutfen marka adini yaziniz!',
                        'Uyari!');
                } else {
                    gdgForm.submit();
                }
            });
        });
    </script>
@endpush
