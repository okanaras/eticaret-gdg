@extends('layouts.admin')


@section('title', $data->title)


@push('css')
    <link rel="stylesheet" href="{{ asset('assets/vendors/select2/select2.min.css') }}">
@endpush


@section('body')
    <div class="card">
        <div class="card-body">

            <h6 class="card-title">{{ $data->title }}</h6>
            <form class="forms-sample row" action="{{ $data->route }}" method="POST" id="gdgForm"
                enctype="multipart/form-data">
                @csrf

                <p class="col-md-2"><b>Indirim Adi :</b>{{ $discount->name }}</p>
                <p class="col-md-2"><b>Indirim Turu
                        :</b>{{ getDiscountType(\App\Enums\DiscountTypeEnum::tryFrom($discount->type)) }}</p>
                <p class="col-md-2"><b>Indirim Degeri :</b>{{ $discount->value }}</p>
                <p class="col-md-2"><b>Minimum Harcama Degeri :</b>{{ $discount->minimum_spend }}</p>
                <p class="col-md-2"><b>Baslangic Tarihi :</b>{{ $discount->start_date }}</p>
                <p class="col-md-2"><b>Bitis Tarihi :</b>{{ $discount->end_date }}</p>

                <div class="col-md-12 my-3">
                    <label for="{{ $data->select_id }}" class="form-label">{{ $data->label }}</label>
                    <select class="js-example-basic-multiple form-select" multiple="multiple" data-width="100%"
                        name="{{ $data->select_name }}[]" id="{{ $data->select_id }}">
                        @foreach ($data->items as $item)
                            <option value="{{ $item->id }}" {{ $item->id }}>
                                {{ $item->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="button" class="btn btn-primary me-2" id="btnSubmit">Kaydet</button>
            </form>

        </div>
    </div>
@endsection


@push('js')
    <script src="{{ asset('assets/vendors/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let btnSubmit = document.querySelector('#btnSubmit');
            let gdgForm = document.querySelector('#gdgForm');
            let data_id = document.querySelector('#{{ $data->select_id }}');

            btnSubmit.addEventListener('click', () => {
                if (data_id.value === '') {
                    toastr.warning('{{ $data->message }}',
                        'Uyari!');
                } else {
                    gdgForm.submit();
                }
            });

            $('{{ $data->select_id }}').select2({
                placeholder: 'asd'
            });
        });
    </script>
@endpush
