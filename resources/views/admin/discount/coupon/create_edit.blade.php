@extends('layouts.admin')


@section('title', 'Indirim Kodu' . (isset($discount) ? 'Guncelleme' : 'Ekleme'))


@push('css')
    <link rel="stylesheet" href="{{ asset('assets/vendors/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/select2/select2.min.css') }}">

    <style>
        .select2-container--focus.select2-container--default .select2-selection--single,
        .select2-container--focus.select2-container--default .select2-selection--multiple {
            border: 1px solid #e9ecef;
        }

        .select2-container .select2-selection--single {
            height: 38px;
        }

        .select2-selection__rendered:focus-visible {
            outline: unset;
        }
    </style>
@endpush


@section('body')
    <div class="card">
        <div class="card-body">

            <h6 class="card-title">Indirim Kodu {{ isset($discount) ? 'Guncelleme' : 'Ekleme' }}</h6>

            @php
                $curenntRoute = !isset($discount)
                    ? route('admin.discount-coupons.store')
                    : route('admin.discount-coupons.update', $discount->id);
            @endphp

            <form class="forms-sample row" action="{{ $curenntRoute }}" method="POST" id="gdgForm"
                enctype="multipart/form-data">
                @csrf
                @isset($discount)
                    @method('PUT')
                @endisset

                <div class="col-md-6 mb-3">
                    <label for="code" class="form-label">Indirim Kodu</label>
                    <input type="text" class="form-control" id="code" autocomplete="off" placeholder="Indirim Kodu"
                        name="code" value="{{ isset($discount) ? $discount->code : old('code') }}">
                    @error('code')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="discount_id" class="form-label">Indirim Tanimlamasi</label>
                    <select class="form-select select-discounts" name="discount_id" id="discount_id" data-width="100%">
                        <option selected='selected' value="-1">Indirim Turu Seciniz</option>
                        @foreach ($discounts as $itemDiscount)
                            <option value="{{ $itemDiscount->id }}"
                                {{ isset($discount) && $itemDiscount->id === $discount->discount_id ? 'selected' : '' }}>
                                {{ $itemDiscount->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('discount_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="usage_limit" class="form-label">Max Kullanim Limiti</label>
                    <input type="number" class="form-control" id="usage_limit" placeholder="Max Kullanim Limiti"
                        name="usage_limit" value="{{ isset($discount) ? $discount->usage_limit : old('usage_limit') }}">
                    <div>Kullanim Sayisi: {{ isset($discount) ? $discount->used_count : '0' }}</div>
                    @error('usage_limit')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label for="expiry_date" class="form-label">Indirim Kodu Son Kullanim Tarihi</label>

                    <div class="input-group flatpickr flatpickr-date">
                        <input type="text" class="form-control flatpickr-input active" name="expiry_date"
                            id="expiry_date" placeholder="Indirim Kodu Son Kullanim Tarihi"
                            value="{{ isset($discount) ? $discount->expiry_date : old('expiry_date') }}" data-input="">
                        <span class="input-group-text input-group-addon" data-toggle=""><i
                                data-feather="calendar"></i></span>
                    </div>
                </div>

                <button type="button" class="btn btn-primary me-2" id="btnSubmit">Kaydet</button>
            </form>

        </div>
    </div>
@endsection


@push('js')
    <script src="{{ asset('assets/vendors/pickr/pickr.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if ($(".select-discounts").length) {
                $(".select-discounts").select2();
            }

            let btnSubmit = document.querySelector('#btnSubmit');
            let gdgForm = document.querySelector('#gdgForm');
            let code = document.querySelector('#code');
            let discount_id = document.querySelector('#discount_id');
            let usage_limit = document.querySelector('#usage_limit');
            let expiry_date = document.querySelector('#expiry_date');


            btnSubmit.addEventListener('click', () => {
                if (code.value.trim().length < 1) {
                    toastr.warning("Lutfen indirim Code'unu giriniz!",
                        'Uyari!');
                } else if (discount_id.value === '-1') {
                    toastr.warning('Lutfen indirim tanimlamasi seciniz!',
                        'Uyari!');
                } else if (usage_limit.value.trim().length < 1) {
                    toastr.warning('Lutfen maksimum indirim degerini giriniz!',
                        'Uyari!');
                } else if (expiry_date.value.trim().length < 1) {
                    toastr.warning('Lutfen indirim kodunun son kullanma tarihini seciniz!',
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
