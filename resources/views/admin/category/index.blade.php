@extends('layouts.admin')


@section('title', 'Kategori Listesi')


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
            <h6 class="card-title">Kategori Listesi</h6>
            <x-filter-form :filters="$filters" action="{{ route('admin.category.index') }}" />
            <div class="table-responsive pt-3">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th @class([
                                'order-by',
                                'text-primary fw-bolder' =>
                                    request('order_by') == 'id' || is_null(request('order_by')),
                            ]) data-order="id"># {!! (request('order_by') == 'id' && request('order_direction') === 'asc') || request('order_by') == null
                                ? '<i class="size-14" data-feather="arrow-down-circle"></i>'
                                : (request('order_by') == 'id' && request('order_direction') === 'desc'
                                    ? '<i class="size-14" data-feather="arrow-up-circle"></i>'
                                    : '') !!}</th>
                            <th @class([
                                'order-by',
                                'text-primary fw-bolder' => request('order_by') == 'name',
                            ]) data-order="name">Kategori Adi {!! request('order_by') == 'name' && request('order_direction') === 'asc'
                                ? '<i class="size-14" data-feather="arrow-down-circle"></i>'
                                : (request('order_by') == 'name' && request('order_direction') === 'desc'
                                    ? '<i class="size-14" data-feather="arrow-up-circle"></i>'
                                    : '') !!}</th>
                            <th>Slug</th>
                            <th @class([
                                'order-by',
                                'text-primary fw-bolder' => request('order_by') == 'parent_id',
                            ]) data-order="parent_id">Ust Kategori {!! request('order_by') == 'parent_id' && request('order_direction') === 'asc'
                                ? '<i class="size-14" data-feather="arrow-down-circle"></i>'
                                : (request('order_by') == 'parent_id' && request('order_direction') === 'desc'
                                    ? '<i class="size-14" data-feather="arrow-up-circle"></i>'
                                    : '') !!}</th>
                            <th @class([
                                'order-by',
                                'text-primary fw-bolder' => request('order_by') == 'status',
                            ]) data-order="status">Durum {!! request('order_by') == 'status' && request('order_direction') === 'asc'
                                ? '<i class="size-14" data-feather="arrow-down-circle"></i>'
                                : (request('order_by') == 'status' && request('order_direction') === 'desc'
                                    ? '<i class="size-14" data-feather="arrow-up-circle"></i>'
                                    : '') !!}</th>
                            <th>Islemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->slug }}</td>
                                <td>{{ $category->parentCategory?->name }}</td> {{-- parent bos olacabilecginden dolayi ? koyduk --}}
                                <td>
                                    @if ($category->status)
                                        <a href="javascript:void(0)" class="btn btn-inverse-success btn-change-status"
                                            data-id="{{ $category->id }}" data-name="{{ $category->name }}">Aktif</a>
                                    @else
                                        <a href="javascript:void(0)" class="btn btn-inverse-danger btn-change-status"
                                            data-id="{{ $category->id }}" data-name="{{ $category->name }}">Pasif</a>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.category.edit', ['category' => $category->id]) }}"><i
                                            data-feather="edit" class="text-warning"></i></a>
                                    <a href="javascript:void(0)"><i data-feather="trash"
                                            class="text-danger btn-delete-category" data-id="{{ $category->id }}"
                                            data-name="{{ $category->name }}"></i></a>
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
                    {{ $categories->WithQueryString()->links() }}
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

                if (element.classList.contains('btn-delete-category')) {
                    Swal.fire({
                        title: " '" + dataName + "' kategorisini silmek istediginize emin misiniz?",
                        showCancelButton: true,
                        confirmButtonText: "Evet",
                        cancelButtonText: "Hayir"
                    }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                            let route =
                                '{{ route('admin.category.destroy', ['category' => ':category']) }}'
                            route = route.replace(':category', dataID)

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

                            let route = "{{ route('admin.category.change-status') }}";

                            fetch(route, data)
                                .then(response => {
                                    if (!response.ok) {
                                        toastr.error(
                                            'Kategori status guncellenemedi, hata alindi!',
                                            'Hata');
                                        console.error(response);
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
                                        `Kategori ${element.textContent.toLowerCase()} olarak guncellendi!`,
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

                function removeIElements() {
                    let findIElement = document.querySelectorAll('th svg');
                    findIElement.forEach(i => i.remove());
                }
            });
        });
    </script>
@endpush
