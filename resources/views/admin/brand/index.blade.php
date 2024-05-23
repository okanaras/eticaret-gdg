@extends('layouts.admin')


@section('title', 'Marka Listesi')


@push('css')
@endpush


@section('body')
    <div class="card">
        <div class="card-body">
            <h6 class="card-title">Marka Listesi</h6>
            <div class="table-responsive pt-3">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Logo</th>
                            <th>Sira Numarasi</th>
                            <th>Marka Adi</th>
                            <th>Slug</th>
                            <th>Durum</th>
                            <th>One Cikarilma Durumu</th>
                            <th>Islemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($brands as $brand)
                            <tr>
                                <td>{{ $brand->id }}</td>
                                <td><img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}" width="100">
                                </td>
                                <td>{{ $brand->order }}</td>
                                <td>{{ $brand->name }}</td>
                                <td>{{ $brand->slug }}</td>
                                <td>
                                    @if ($brand->status)
                                        <a href="javascript:void(0)" class="btn btn-inverse-success btn-change-status"
                                            data-id="{{ $brand->id }}" data-name="{{ $brand->name }}">Aktif</a>
                                    @else
                                        <a href="javascript:void(0)" class="btn btn-inverse-danger btn-change-status"
                                            data-id="{{ $brand->id }}" data-name="{{ $brand->name }}">Pasif</a>
                                    @endif
                                </td>
                                <td>
                                    @if ($brand->is_featured)
                                        <a href="javascript:void(0)" class="btn btn-inverse-success btn-change-is-featured"
                                            data-id="{{ $brand->id }}" data-name="{{ $brand->name }}">Evet</a>
                                    @else
                                        <a href="javascript:void(0)" class="btn btn-inverse-danger btn-change-is-featured"
                                            data-id="{{ $brand->id }}" data-name="{{ $brand->name }}">Hayir</a>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.brand.edit', ['brand' => $brand->id]) }}"><i
                                            data-feather="edit" class="text-warning"></i></a>
                                    <a href="javascript:void(0)"><i data-feather="trash"
                                            class="text-danger btn-delete-brand" data-id="{{ $brand->id }}"
                                            data-name="{{ $brand->name }}"></i></a>
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
                    {{ $brands->links() }}
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

                if (element.classList.contains('btn-delete-brand')) {
                    Swal.fire({
                        title: " '" + dataName + "' markasini silmek istediginize emin misiniz?",
                        showCancelButton: true,
                        confirmButtonText: "Evet",
                        cancelButtonText: "Hayir"
                    }).then((result) => {
                        /* Read more about isConfirmed, isDenied below */
                        if (result.isConfirmed) {
                            let route =
                                '{{ route('admin.brand.destroy', ['brand' => ':brand']) }}'
                            route = route.replace(':brand', dataID)

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

                            let route = "{{ route('admin.brand.change-status') }}";

                            fetch(route, data)
                                .then(response => {
                                    if (!response.ok) {
                                        toastr.error(
                                            'Marka status guncellenemedi, hata alindi!',
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
                                        `Marka ${element.textContent.toLowerCase()} olarak guncellendi!`,
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
            });
        });
    </script>
@endpush
