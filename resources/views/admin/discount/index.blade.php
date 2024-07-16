@extends('layouts.admin')


@section('title', 'Indirim Listesi')


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
            <h6 class="card-title">Indirim Listesi</h6>
            <x-filter-form :filters="$filters" action="{{ route('admin.discount.index') }}" />

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
                                'text-primary fw-bolder' => request('order_by') == 'name',
                            ]) data-order="name">
                                Indirim Adi {!! request('order_by') == 'name' && request('order_direction') === 'asc'
                                    ? '<i class="size-14" data-feather="arrow-down-circle"></i>'
                                    : (request('order_by') == 'name' && request('order_direction') === 'desc'
                                        ? '<i class="size-14" data-feather="arrow-up-circle"></i>'
                                        : '') !!}
                            </th>

                            <th @class([
                                'order-by',
                                'text-primary fw-bolder' => request('order_by') == 'value',
                            ]) data-order="value">
                                Indirim Degeri {!! request('order_by') == 'value' && request('order_direction') === 'asc'
                                    ? '<i class="size-14" data-feather="arrow-down-circle"></i>'
                                    : (request('order_by') == 'value' && request('order_direction') === 'desc'
                                        ? '<i class="size-14" data-feather="arrow-up-circle"></i>'
                                        : '') !!}
                            </th>

                            <th @class([
                                'order-by',
                                'text-primary fw-bolder' => request('order_by') == 'type',
                            ]) data-order="type">
                                Indirim Turu {!! request('order_by') == 'type' && request('order_direction') === 'asc'
                                    ? '<i class="size-14" data-feather="arrow-down-circle"></i>'
                                    : (request('order_by') == 'type' && request('order_direction') === 'desc'
                                        ? '<i class="size-14" data-feather="arrow-up-circle"></i>'
                                        : '') !!}
                            </th>

                            <th @class([
                                'order-by',
                                'text-primary fw-bolder' => request('order_by') == 'status',
                            ]) data-order="status">
                                Durum {!! request('order_by') == 'status' && request('order_direction') === 'asc'
                                    ? '<i class="size-14" data-feather="arrow-down-circle"></i>'
                                    : (request('order_by') == 'status' && request('order_direction') === 'desc'
                                        ? '<i class="size-14" data-feather="arrow-up-circle"></i>'
                                        : '') !!}
                            </th>

                            <th @class([
                                'order-by',
                                'text-primary fw-bolder' => request('order_by') == 'start_date',
                            ]) data-order="start_date">
                                Indirim Baslangic Tarihi {!! request('order_by') == 'start_date' && request('order_direction') === 'asc'
                                    ? '<i class="size-14" data-feather="arrow-down-circle"></i>'
                                    : (request('order_by') == 'start_date' && request('order_direction') === 'desc'
                                        ? '<i class="size-14" data-feather="arrow-up-circle"></i>'
                                        : '') !!}
                            </th>
                            <th @class([
                                'order-by',
                                'text-primary fw-bolder' => request('order_by') == 'end_date',
                            ]) data-order="end_date">
                                Indirim Bitis Tarihi {!! request('order_by') == 'end_date' && request('order_direction') === 'asc'
                                    ? '<i class="size-14" data-feather="arrow-down-circle"></i>'
                                    : (request('order_by') == 'end_date' && request('order_direction') === 'desc'
                                        ? '<i class="size-14" data-feather="arrow-up-circle"></i>'
                                        : '') !!}
                            </th>

                            <th>Islemler</th>
                            <th>Atamalar</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($discounts as $discount)
                            <tr>
                                <td>{{ $discount->id }}</td>
                                <td>{{ $discount->name }}</td>
                                <td>{{ getDiscountType(\App\Enums\DiscountTypeEnum::tryFrom($discount->type)) }}</td>
                                <td>{{ $discount->value }}</td>
                                <td>
                                    @if ($discount->status)
                                        <a href="javascript:void(0)" class="btn btn-inverse-success btn-change-status"
                                            data-id="{{ $discount->id }}" data-name="{{ $discount->name }}">Aktif</a>
                                    @else
                                        <a href="javascript:void(0)" class="btn btn-inverse-danger btn-change-status"
                                            data-id="{{ $discount->id }}" data-name="{{ $discount->name }}">Pasif</a>
                                    @endif
                                </td>
                                <td>{{ $discount->start_date }}</td>
                                <td>{{ $discount->end_date }}</td>
                                <td>
                                    <a href="{{ route('admin.discount.edit', ['discount' => $discount->id]) }}">
                                        <i data-feather="edit" class="text-warning"></i>
                                    </a>
                                    <a href="javascript:void(0)">
                                        <i data-feather="trash" class="text-danger btn-delete-discount"
                                            data-id="{{ $discount->id }}" data-name="{{ $discount->name }}">
                                        </i>
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('admin.discount.assign-products', $discount->id) }}"
                                        class="btn btn-primary p-1" title="Urune Indirim Atama">
                                        <i data-feather="box"></i>
                                    </a>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <form action="" method="POST" id="deleteForm">
                    @csrf
                    @method('DELETE')
                </form>

                <div class="col-6 mx-auto mt-3">
                    {{-- {{ $discounts->appends(request()->query())->links() }} --}}
                    {{ $discounts->WithQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>

@endsection


@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let deleteForm = document.querySelector('#deleteForm');
            let defaultOrderDirection = "{{ request('order_direction') }}";

            feather.replace();

            document.querySelector('.table').addEventListener('click', (event) => {
                let element = event.target;

                let dataID = element.getAttribute('data-id');
                let dataName = element.getAttribute('data-name');

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
                                '{{ route('admin.discount.destroy', ['discount' => ':discount']) }}'
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

                if (element.classList.contains('btn-change-status')) {
                    Swal.fire({
                        title: " '" + dataName +
                            "' statusunu degistirmek istediginize emin misiniz?",
                        showCancelButton: true,
                        confirmButtonText: "Evet",
                        cancelButtonText: "Hayir"
                    }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                            let body = {
                                id: dataID
                            };

                            let data = {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify(body)
                            }

                            let route = "{{ route('admin.discount.change-status') }}";

                            fetch(route, data)
                                .then(response => {
                                    if (!response.ok) {
                                        return response.json()
                                            .then(error => {
                                                toastr.error(
                                                    `Indirim status guncellenemedi. ${error?.message || 'Hata alindi!'}`,
                                                    'Hata');
                                            });
                                    }

                                    return response.json();
                                })
                                .then(data => {
                                    element.textContent = data.status ? "Aktif" : "Pasif";

                                    if (data.status) {
                                        element.classList.add('btn-inverse-success');
                                        element.classList.remove('btn-inverse-danger');

                                    } else {
                                        element.classList.remove('btn-inverse-success');
                                        element.classList.add('btn-inverse-danger');
                                    }
                                    toastr.success(
                                        `Indirim ${element.textContent.toLowerCase()} olarak guncellendi!`,
                                        'Basarili');
                                })


                        } else if (result.dismiss) {
                            toastr.info("Herhangi bir islem gerceklestirilmedi!", 'Bilgi');
                        }
                    });

                }

                if (element.classList.contains('btn-change-is-featured')) {
                    Swal.fire({
                        title: " '" + dataName +
                            "' statusunu degistirmek istediginize emin misiniz?",
                        showCancelButton: true,
                        confirmButtonText: "Evet",
                        cancelButtonText: "Hayir"
                    }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                            let body = {
                                id: dataID
                            };

                            let data = {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify(body)
                            }

                            let route = "{{ route('admin.brand.change-is-featured') }}";

                            fetch(route, data)
                                .then(response => {
                                    if (!response.ok) {
                                        toastr.error(
                                            'Marka onde cikarilma durumu guncellenemedi, hata alindi!',
                                            'Hata');
                                        console.error(response);
                                    }

                                    return response.json();
                                })
                                .then(data => {
                                    element.textContent = data.is_featured ? "Evet" : "Hayir";

                                    if (data.is_featured) {
                                        element.classList.add('btn-inverse-success');
                                        element.classList.remove('btn-inverse-danger');

                                    } else {
                                        element.classList.remove('btn-inverse-success');
                                        element.classList.add('btn-inverse-danger');
                                    }
                                    toastr.success(
                                        `Marka is featured ${element.textContent.toLowerCase()} olarak guncellendi!`,
                                        'Basarili');
                                })


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
