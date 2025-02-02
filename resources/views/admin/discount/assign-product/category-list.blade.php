@extends('layouts.admin')


@section('title', 'Indirimli Kategori Listesi')


@push('css')
    <style>
        .size-14 {
            width: 16px;
            height: 16px;
            margin-left: 1px;
        }

        th {
            cursor: pointer;
        }

        .order-by:hover {
            color: #6571ffd9;
            font-weight: bolder;
        }
    </style>
@endpush


@section('body')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">{{ $discount->name }}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">Hakkinda Bilgiler</h6>
                            <p><b>Indirim Adi :</b>{{ $discount->name }}</p>
                            <p><b>Indirim Turu
                                    :</b>{{ getDiscountType(\App\Enums\DiscountTypeEnum::tryFrom($discount->type)) }}</p>
                            <p><b>Indirim Degeri :</b>{{ $discount->value }}</p>
                            <p><b>Minimum Harcama Degeri :</b>{{ $discount->minimum_spend }}</p>
                            <p><b>Baslangic Tarihi :</b>{{ $discount->start_date }}</p>
                            <p><b>Bitis Tarihi :</b>{{ $discount->end_date }}</p>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <x-filter-form :filters="$filters" custom-class="col-md-4"
                        action="{{ route('admin.discount.show-categories-list', $discount->id) }}" />
                </div>
                <div class="col-md-12">
                    <h6 class="card-title">Indirim Listesi</h6>

                    <div class="table-responsive pt-3">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th @class([
                                        'order-by',
                                        'text-primary fw-bolder' =>
                                            request('order_by') == 'categories.id' || is_null(request('order_by')),
                                    ]) data-order="categories.id">#
                                        {!! (request('order_by') == 'categories.id' && request('order_direction') === 'asc') ||
                                        request('order_by') == null
                                            ? '<i class="size-14" data-feather="arrow-down-circle"></i>'
                                            : (request('order_by') == 'categories.id' && request('order_direction') === 'desc'
                                                ? '<i class="size-14" data-feather="arrow-up-circle"></i>'
                                                : '') !!}
                                    </th>

                                    <th @class([
                                        'order-by',
                                        'text-primary fw-bolder' => request('order_by') == 'categories.name',
                                    ]) data-order="categories.name">
                                        Indirimli Kategori Adi {!! request('order_by') == 'categories.name' && request('order_direction') === 'asc'
                                            ? '<i class="size-14" data-feather="arrow-down-circle"></i>'
                                            : (request('order_by') == 'categories.name' && request('order_direction') === 'desc'
                                                ? '<i class="size-14" data-feather="arrow-up-circle"></i>'
                                                : '') !!}
                                    </th>

                                    <th @class([
                                        'order-by',
                                        'text-primary fw-bolder' => request('order_by') == 'categories.parent_id',
                                    ]) data-order="categories.parent_id">
                                        Ust Kategori {!! request('order_by') == 'categories.parent_id' && request('order_direction') === 'asc'
                                            ? '<i class="size-14" data-feather="arrow-down-circle"></i>'
                                            : (request('order_by') == 'categories.parent_id' && request('order_direction') === 'desc'
                                                ? '<i class="size-14" data-feather="arrow-up-circle"></i>'
                                                : '') !!}
                                    </th>

                                    <th>Islemler</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($discounts as $item)
                                    <tr @class([
                                        'bg-info' => !is_null($item->deleted_at),
                                    ])>
                                        <td>{{ $item->dcId }}</td>
                                        <td>{{ $item->cName }}</td>
                                        <td>{{ $item->parentCategoryName }}</td>
                                        <td>
                                            @if (is_null($item->deleted_at))
                                                <a href="javascript:void(0)">
                                                    <i data-feather="trash" class="text-danger btn-delete-discount"
                                                        data-discount-id="{{ $discount->id }}"
                                                        data-category-id="{{ $item->cId }}"
                                                        data-name="{{ $item->cName }}">
                                                    </i>
                                                </a>
                                            @else
                                                <a href="javascript:void(0)">
                                                    <i data-feather="rotate-cw" class="text-success btn-restore-discount"
                                                        data-discount-id="{{ $discount->id }}"
                                                        data-discount-category-id="{{ $item->dcId }}"
                                                        data-category-id="{{ $item->cId }}"
                                                        data-name="{{ $item->cName }}">
                                                    </i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <form action="" method="POST" id="deleteForm">
                            @csrf
                            @method('DELETE')
                        </form>

                        <form action="" method="POST" id="putForm">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="discount_category_id" id="discount_category_id">
                        </form>

                        <div class="col-6 mx-auto mt-3">
                            {{-- {{ $discounts->appends(request()->query())->links() }} --}}
                            {{ $discounts->WithQueryString()->links() }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let deleteForm = document.querySelector('#deleteForm');
            let putForm = document.querySelector('#putForm');
            let discountCategoryIdElement = document.querySelector('#discount_category_id');
            let defaultOrderDirection = "{{ request('order_direction') }}";

            feather.replace();

            document.querySelector('.table').addEventListener('click', (event) => {
                let element = event.target;

                let dataDiscountID = element.getAttribute('data-discount-id');
                let dataCategoryID = element.getAttribute('data-category-id');
                let dataName = element.getAttribute('data-name');
                discountCategoryIdElement.value = element.getAttribute('data-discount-category-id');

                if (element.classList.contains('btn-delete-discount')) {
                    Swal.fire({
                        title: " '" + dataName + "' indirimini silmek istediginize emin misiniz?",
                        showCancelButton: true,
                        confirmButtonText: "Evet",
                        cancelButtonText: "Hayir"
                    }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                            let route =
                                '{{ route('admin.discount.remove-category', ['discount' => ':discount', 'category' => ':category']) }}'
                            route = route.replace(':discount', dataDiscountID)
                                .replace(':category', dataCategoryID);

                            deleteForm.action = route;

                            setTimeout(() => {
                                deleteForm.submit();
                            }, 100);

                        } else if (result.dismiss) {
                            toastr.info("Herhangi bir islem gerceklestirilmedi!", 'Bilgi');
                        }
                    });
                }

                if (element.classList.contains('btn-restore-discount')) {
                    Swal.fire({
                        title: " '" + dataName +
                            "' indirimini geri almak istediginize emin misiniz?",
                        showCancelButton: true,
                        confirmButtonText: "Evet",
                        cancelButtonText: "Hayir"
                    }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                            let route =
                                '{{ route('admin.discount.restore-category', ['discount' => ':discount', 'category' => ':category']) }}'
                            route = route.replace(':discount', dataDiscountID)
                                .replace(':category', dataCategoryID);

                            putForm.action = route;

                            setTimeout(() => {
                                putForm.submit();
                            }, 100);

                        } else if (result.dismiss) {
                            toastr.info("Herhangi bir islem gerceklestirilmedi!", 'Bilgi');
                        }
                    });
                }

                if (element.classList.contains('order-by')) {
                    let dataOrder = element.getAttribute('data-order');
                    let orderByElement = document.querySelector('#order_by');
                    let orderDirectionElement = document.querySelector('#order_direction');
                    let filterForm = document.querySelector('#filter-form');

                    orderByElement.value = dataOrder;
                    removeIElements();

                    if (defaultOrderDirection === '' || defaultOrderDirection === null ||
                        defaultOrderDirection === undefined) {
                        defaultOrderDirection = 'desc';

                        let iElement = document.createElement('i');
                        iElement.setAttribute('data-feather', 'arrow-up-circle');
                        iElement.classList.add('size-14');
                        element.appendChild(iElement);

                    } else if (defaultOrderDirection === 'asc') {
                        defaultOrderDirection = 'desc';
                        let iElement = document.createElement('i');
                        iElement.setAttribute('data-feather', 'arrow-up-circle');
                        iElement.classList.add('size-14');
                        element.appendChild(iElement);
                    } else {
                        defaultOrderDirection = 'asc';
                        let iElement = document.createElement('i');
                        iElement.setAttribute('data-feather', 'arrow-down-circle');
                        iElement.classList.add('size-14');
                        element.appendChild(iElement);
                    }

                    orderDirectionElement.value = defaultOrderDirection;
                    feather.replace();
                    filterForm.submit();

                }
            });

            function removeIElements() {
                let findIElement = document.querySelectorAll('th svg');
                findIElement.forEach(i => i.remove());
            }
        });
    </script>
@endpush
