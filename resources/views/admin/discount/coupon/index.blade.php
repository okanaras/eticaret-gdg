@extends('layouts.admin')


@section('title', 'Indirim Kodu Listesi')


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
            <h6 class="card-title">Indirim Kodu Listesi</h6>
            <x-filter-form :filters="$filters" action="{{ route('admin.discount-coupons.index') }}" />

            <div class="table-responsive pt-3">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th @class([
                                'order-by',
                                'text-primary fw-bolder' =>
                                    request('order_by') == 'id' || is_null(request('order_by')),
                            ]) data-order="id">#
                                {!! (request('order_by') == 'id' && request('order_direction') === 'asc') || request('order_by') == null
                                    ? '<i class="size-14" data-feather="arrow-down-circle"></i>'
                                    : (request('order_by') == 'id' && request('order_direction') === 'desc'
                                        ? '<i class="size-14" data-feather="arrow-up-circle"></i>'
                                        : '') !!}
                            </th>

                            <th @class([
                                'order-by',
                                'text-primary fw-bolder' => request('order_by') == 'code',
                            ]) data-order="code">
                                Indirim Kodu {!! request('order_by') == 'code' && request('order_direction') === 'asc'
                                    ? '<i class="size-14" data-feather="arrow-down-circle"></i>'
                                    : (request('order_by') == 'code' && request('order_direction') === 'desc'
                                        ? '<i class="size-14" data-feather="arrow-up-circle"></i>'
                                        : '') !!}
                            </th>

                            <th @class([
                                'order-by',
                                'text-primary fw-bolder' => request('order_by') == 'discount_id',
                            ]) data-order="discount_id">
                                Indirim Tanimlamasi {!! request('order_by') == 'discount_id' && request('order_direction') === 'asc'
                                    ? '<i class="size-14" data-feather="arrow-down-circle"></i>'
                                    : (request('order_by') == 'discount_id' && request('order_direction') === 'desc'
                                        ? '<i class="size-14" data-feather="arrow-up-circle"></i>'
                                        : '') !!}
                            </th>

                            <th @class([
                                'order-by',
                                'text-primary fw-bolder' => request('order_by') == 'usage_limit',
                            ]) data-order="usage_limit">
                                Maksimum Kullanim Degeri {!! request('order_by') == 'usage_limit' && request('order_direction') === 'asc'
                                    ? '<i class="size-14" data-feather="arrow-down-circle"></i>'
                                    : (request('order_by') == 'usage_limit' && request('order_direction') === 'desc'
                                        ? '<i class="size-14" data-feather="arrow-up-circle"></i>'
                                        : '') !!}
                            </th>

                            <th @class([
                                'order-by',
                                'text-primary fw-bolder' => request('order_by') == 'used_count',
                            ]) data-order="used_count">
                                Kullanim Miktari {!! request('order_by') == 'used_count' && request('order_direction') === 'asc'
                                    ? '<i class="size-14" data-feather="arrow-down-circle"></i>'
                                    : (request('order_by') == 'used_count' && request('order_direction') === 'desc'
                                        ? '<i class="size-14" data-feather="arrow-up-circle"></i>'
                                        : '') !!}
                            </th>

                            <th @class([
                                'order-by',
                                'text-primary fw-bolder' => request('order_by') == 'expiry_date',
                            ]) data-order="expiry_date">
                                Indirim Kodu Son Kullanim Tarihi {!! request('order_by') == 'expiry_date' && request('order_direction') === 'asc'
                                    ? '<i class="size-14" data-feather="arrow-down-circle"></i>'
                                    : (request('order_by') == 'expiry_date' && request('order_direction') === 'desc'
                                        ? '<i class="size-14" data-feather="arrow-up-circle"></i>'
                                        : '') !!}
                            </th>

                            <th>Islemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($coupons as $coupon)
                            <tr @class([
                                'bg-info' => !is_null($coupon->deleted_at),
                            ])>
                                <td>{{ $coupon->id }}</td>
                                <td>{{ $coupon->code }}</td>
                                <td>{{ $coupon->discount->name }}</td>
                                <td>{{ $coupon->usage_limit }}</td>
                                <td>{{ $coupon->used_count }}</td>
                                <td>{{ $coupon->expiry_date }}</td>
                                <td>
                                    <a
                                        href="{{ route('admin.discount-coupons.edit', ['discount_coupon' => $coupon->id]) }}">
                                        <i data-feather="edit" class="text-warning"></i>
                                    </a>

                                    @if (is_null($coupon->deleted_at))
                                        <a href="javascript:void(0)">
                                            <i data-feather="trash" class="text-danger btn-delete-coupon"
                                                data-id="{{ $coupon->id }}" data-name="{{ $coupon->code }}">
                                            </i>
                                        </a>
                                    @else
                                        <a href="javascript:void(0)">
                                            <i data-feather="rotate-cw" class="text-success btn-restore-coupon"
                                                data-coupon-id="{{ $coupon->id }}" data-name="{{ $coupon->code }}">
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
                </form>

                <div class="col-6 mx-auto mt-3">
                    {{-- {{ $coupons->appends(request()->query())->links() }} --}}
                    {{ $coupons->WithQueryString()->links() }}
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
            let defaultOrderDirection = "{{ request('order_direction') }}";

            feather.replace();

            document.querySelector('.table').addEventListener('click', (event) => {
                let element = event.target;

                let dataID = element.getAttribute('data-id');
                let dataName = element.getAttribute('data-name');

                if (element.classList.contains('btn-delete-coupon')) {
                    Swal.fire({
                        title: " '" + dataName + "' indirimini silmek istediginize emin misiniz?",
                        showCancelButton: true,
                        confirmButtonText: "Evet",
                        cancelButtonText: "Hayir"
                    }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                            let route =
                                '{{ route('admin.discount-coupons.destroy', ['discount_coupon' => ':discount']) }}'
                            route = route.replace(':discount', dataID)

                            deleteForm.action = route;

                            setTimeout(() => {
                                deleteForm.submit();
                            }, 100);

                        } else if (result.dismiss) {
                            toastr.info("Herhangi bir islem gerceklestirilmedi!", 'Bilgi');
                        }
                    });
                }

                if (element.classList.contains('btn-restore-coupon')) {
                    Swal.fire({
                        title: " '" + dataName +
                            "' indirim kodunu geri almak istediginize emin misiniz?",
                        showCancelButton: true,
                        confirmButtonText: "Evet",
                        cancelButtonText: "Hayir"
                    }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {

                            let dataDiscountID = element.getAttribute('data-coupon-id');
                            let route =
                                '{{ route('admin.discount-coupons.restore', ['discount_coupon' => ':discount_coupon']) }}'
                            route = route.replace(':discount_coupon', dataDiscountID);

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
