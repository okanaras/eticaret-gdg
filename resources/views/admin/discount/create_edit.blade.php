@extends('layouts.admin')


@section('title', 'Indirim ' . (isset($discount) ? 'Guncelleme' : 'Ekleme'))


@push('css')
    <link rel="stylesheet" href="{{ asset('assets/vendors/flatpickr/flatpickr.min.css') }}">
@endpush


@section('body')
    <div class="card">
        <div class="card-body">

            <h6 class="card-title">Indirim {{ isset($discount) ? 'Guncelleme' : 'Ekleme' }}</h6>

            @php
                $curenntRoute = !isset($discount)
                    ? route('admin.discount.store')
                    : route('admin.discount.update', $discount->id);
            @endphp

            <form class="forms-sample row" action="{{ $curenntRoute }}" method="POST" id="gdgForm"
                enctype="multipart/form-data">
                @csrf
                @isset($discount)
                    @method('PUT')
                @endisset

                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Indirim Adi</label>
                    <input type="text" class="form-control" id="name" autocomplete="off" placeholder="Indirim Adi"
                        name="name" value="{{ isset($discount) ? $discount->name : old('name') }}">
                    @error('name')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="type" class="form-label">Indirim Turu</label>
                    <select class="form-select" name="type" id="type">
                        <option selected='selected' value="-1">Indirim Turu Seciniz</option>
                        @foreach ($types as $type)
                            <option value="{{ $type->value }}"
                                {{ isset($discount) && $type->value === $discount->type ? 'selected' : '' }}>
                                {{ getDiscountType($type) }}
                            </option>
                        @endforeach
                    </select>
                    @error('type')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="value" class="form-label">Indirim Degeri</label>
                    <input type="number" class="form-control" id="value" placeholder="Indirim Degeri" name="value"
                        value="{{ isset($discount) ? $discount->value : old('value') }}">
                    @error('value')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="minimum_spend" class="form-label">Minimum Harcama Degeri</label>
                    <input type="number" class="form-control" id="minimum_spend" placeholder="Minimum Harcama Degeri"
                        name="minimum_spend"
                        value="{{ isset($discount) ? $discount->minimum_spend : old('minimum_spend') }}">
                    @error('minimum_spend')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="start_date" class="form-label">Indirim Baslangic Tarihi</label>

                    <div class="input-group flatpickr flatpickr-date">
                        <input type="text" class="form-control flatpickr-input active" name="start_date" id="start_date"
                            placeholder="Indirim Baslangic Tarihi"
                            value="{{ isset($discount) ? $discount->start_date : old('start_date') }}" data-input="">
                        <span class="input-group-text input-group-addon" data-toggle=""><i
                                data-feather="calendar"></i></span>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label for="end_date" class="form-label">Indirim Bitis Tarihi</label>

                    <div class="input-group flatpickr flatpickr-date">
                        <input type="text" class="form-control flatpickr-input active" name="end_date" id="end_date"
                            placeholder="Indirim Bitis Tarihi"
                            value="{{ isset($discount) ? $discount->end_date : old('end_date') }}" data-input="">
                        <span class="input-group-text input-group-addon" data-toggle=""><i
                                data-feather="calendar"></i></span>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <input type="checkbox" class="form-check-input" id="status" name="status"
                        {{ isset($discount) ? ($discount->status ? 'checked' : '') : (old('status') ? 'checked' : '') }}>
                    <label class="form-check-label" for="status">
                        Aktif mi?
                    </label>
                    @error('status')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <button type="button" class="btn btn-primary me-2" id="btnSubmit">Kaydet</button>
            </form>

        </div>
    </div>
@endsection


@push('js')
    <script src="{{ asset('assets/vendors/pickr/pickr.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/flatpickr/flatpickr.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let btnSubmit = document.querySelector('#btnSubmit');
            let gdgForm = document.querySelector('#gdgForm');
            let name = document.querySelector('#name');
            let discountValue = document.querySelector('#value');
            let discountType = document.querySelector('#type');
            let startDate = document.querySelector('#start_date');
            let endDate = document.querySelector('#end_date');

            btnSubmit.addEventListener('click', () => {
                if (name.value.trim().length < 1) {
                    toastr.warning('Lutfen indirim adini yaziniz!',
                        'Uyari!');
                } else if (discountType.value === '-1') {
                    toastr.warning('Lutfen indirim turu seciniz!',
                        'Uyari!');
                } else if (discountValue.value.trim().length < 1) {
                    toastr.warning('Lutfen indirim degerini yaziniz!',
                        'Uyari!');
                } else if (startDate.value.trim().length < 1) {
                    toastr.warning('Lutfen indirim baslangic tarihini yaziniz!',
                        'Uyari!');
                } else if (endDate.value.trim().length < 1) {
                    toastr.warning('Lutfen indirim bitis tarihini yaziniz!',
                        'Uyari!');
                } else {
                    gdgForm.submit();
                }
            });

            flatpickr(".flatpickr-date", {
                wrap: true,
                enableTime: false,
                dateFormat: "Y-m-d",
            });
        });
    </script>
@endpush
