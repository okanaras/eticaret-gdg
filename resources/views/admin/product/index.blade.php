@extends('layouts.admin')


@section('title', 'Urun Listesi')


@push('css')
@endpush


@section('body')
    <div class="card">
        <div class="card-body">
            <h6 class="card-title">Urun Listesi</h6>
            <div class="table-responsive pt-3">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Ad</th>
                            <th>Fiyat</th>
                            <th>Kategori</th>
                            <th>Marka</th>
                            <th>Urun Turu</th>
                            <th>Durum</th>
                            <th>Islemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ number_format($product->price, 2) }}</td>
                                <td>{{ $product->category->name }}</td>
                                <td>{{ $product->brand->name }}</td>
                                <td>{{ $product->type->name }}</td>
                                <td>
                                    @if ($product->status)
                                        <a href="javascript:void(0)" class="btn btn-inverse-success btn-change-status"
                                            data-id="{{ $product->id }}" data-name="{{ $product->name }}">Aktif</a>
                                    @else
                                        <a href="javascript:void(0)" class="btn btn-inverse-danger btn-change-status"
                                            data-id="{{ $product->id }}" data-name="{{ $product->name }}">Pasif</a>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.product.edit', ['products_main' => $product->id]) }}"><i
                                            data-feather="edit" class="text-warning"></i></a>
                                    <a href="javascript:void(0)"><i data-feather="trash"
                                            class="text-danger btn-delete-product" data-id="{{ $product->id }}"
                                            data-name="{{ $product->name }}"></i></a>
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
                    {{-- {{ $brands->links() }} --}}
                </div>
            </div>
        </div>
    </div>

@endsection


@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let deleteForm = document.querySelector('#deleteForm');

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
        });
    </script>
@endpush
