@extends('layouts.admin')


@section('title', 'Urun Listesi')


@push('css')
    <style>
        #filter-form {
            height: 80px;
            max-height: max-content;
            min-height: 80px;
            overflow: hidden;
            transition: all 1s ease;
            resize: vertical;
        }

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
            <h6 class="card-title">Urun Listesi</h6>
            <x-filter-form :filters="$filters" action="" customClass="col-md-3" :disableButton="true" />

            <div class="row justify-content-end mt-3">
                <div class="col-md-4">
                    <a href="javascript:void(0)" id="showFilter" class="btn btn-info float-end"
                        title="Tum Filtreleri Goster">Filtreleri
                        Goster</a>
                </div>
            </div>

            <div class="table-responsive pt-3">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="order-by" data-order="products_main.id">#</th>
                            <th class="order-by" data-order="products_main.name">Ad</th>
                            <th>Fiyat</th>
                            <th class="order-by" data-order="categories.name">Kategori</th>
                            <th class="order-by" data-order="brands.name">Marka</th>
                            <th class="order-by" data-order="products_main.type_id">Urun Turu</th>
                            <th class="order-by" data-order="staus">Durum</th>
                            <th>Islemler</th>
                        </tr>
                    </thead>
                    <tbody id="list-body">
                    </tbody>
                </table>
                <form action="" method="POST" id="deleteForm">
                    @csrf
                    @method('DELETE')
                </form>

                <div class="col-6 mx-auto mt-3">

                    <nav class="d-flex justify-items-center justify-content-between">
                        <div class="d-flex justify-content-between flex-fill d-sm-none">
                            <ul class="pagination">
                            </ul>
                        </div>
                        <div class="d-none flex-sm-fill d-sm-flex align-items-sm-center justify-content-sm-between">
                            <div>
                                <ul class="pagination">
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let deleteForm = document.querySelector('#deleteForm');
            const showFilter = document.querySelector('#showFilter');

            document.querySelector('.table').addEventListener('click', (event) => {
                let element = event.target;

                let dataID = element.getAttribute('data-id');
                let dataName = element.getAttribute('data-name');

                if (element.classList.contains('btn-delete-product')) {
                    Swal.fire({
                        title: " '" + dataName + "' markasini silmek istediginize emin misiniz?",
                        showCancelButton: true,
                        confirmButtonText: "Evet",
                        cancelButtonText: "Hayir"
                    }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                            let route =
                                '{{ route('admin.product.destroy', ['products_main' => ':product']) }}'
                            route = route.replace(':product', dataID)

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

                            let route = "{{ route('admin.product.change-status') }}";

                            fetch(route, data)
                                .then(response => {
                                    if (!response.ok) {
                                        toastr.error(
                                            'Urun status guncellenemedi, hata alindi!',
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
                                        `Urun ${element.textContent.toLowerCase()} olarak guncellendi!`,
                                        'Basarili');
                                })


                        } else if (result.dismiss) {
                            toastr.info("Herhangi bir islem gerceklestirilmedi!", 'Bilgi');
                        }
                    });

                }
            });

            showFilter.addEventListener('click', () => {
                const filterForm = document.querySelector('#filter-form');

                if (filterForm.offsetHeight < filterForm.scrollHeight) {
                    filterForm.style.height = `${filterForm.scrollHeight}px`;
                } else {
                    filterForm.style.height = '80px';
                }
            });


        });

        var searchRoute = "{{ route('admin.product.search') }}";
        var editRoute = "{{ route('admin.product.edit', ['products_main' => ':main_id_val']) }}";
        var currentPage = "{{ request()->input('page', 1) }}"; // input if else gibi varsa ilkini, yoksa default al
    </script>
    <script src="{{ asset('assets/js/product/search.js') }}"></script>
@endpush
