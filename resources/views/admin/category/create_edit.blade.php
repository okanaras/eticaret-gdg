@extends('layouts.admin')


@section('title', 'Kategori ' . (isset($category) ? 'Guncelleme' : 'Ekleme'))


@push('css')
@endpush


@section('body')
    <div class="card">
        <div class="card-body">

            <h6 class="card-title">Kategori {{ isset($category) ? 'Guncelleme' : 'Ekleme' }}</h6>

            @php
                $curenntRoute = !isset($category)
                    ? route('admin.category.store')
                    : route('admin.category.update', $category->id);
            @endphp

            <form class="forms-sample" action="{{ $curenntRoute }}" method="POST" id="gdgForm">
                @csrf
                @isset($category)
                    @method('PUT')
                @endisset
                <div class="mb-3">
                    <label for="name" class="form-label">Kategori Adi</label>
                    <input type="text" class="form-control" id="name" autocomplete="off" placeholder="Kategori Adi"
                        name="name" value="{{ isset($category) ? $category->name : old('name') }}">
                    @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" class="form-control" id="slug" placeholder="Slug" name="slug"
                        value="{{ isset($category) ? $category->slug : old('slug') }}">
                    @error('slug')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="status" name="status"
                        {{ isset($category) ? ($category->status ? 'checked' : '') : (old('status') ? 'checked' : '') }}>
                    <label class="form-check-label" for="status">
                        Aktif mi?
                    </label>
                    @error('status')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="short_description" class="form-label">Kisa Aciklama</label>
                    <textarea class="form-control" name="short_description" id="short_description" rows="3"> {{ isset($category) ? $category->short_description : old('short_description') }}</textarea>
                    @error('short_description')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Aciklama</label>
                    <textarea class="form-control" name="description" id="description" rows="7"> {{ isset($category) ? $category->description : old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="parent_id" class="form-label">Ust Kategory</label>
                    <select class="form-select" name="parent_id" id="parent_id">
                        <option selected='selected' value="-1">Ust Kategori Seciniz</option>
                        @foreach ($categories as $pCategory)
                            <option value="{{ $pCategory->id }}"
                                {{ isset($category) && $pCategory->id === $category->parent_id ? 'selected' : '' }}>
                                {{ $pCategory->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
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
                    toastr.warning('Lutfen kategori adini yaziniz!',
                        'Uyari!');
                } else {
                    gdgForm.submit();
                }
            });
        });
    </script>
@endpush
