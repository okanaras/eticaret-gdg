@extends('layouts.admin')


@section('title', 'Kategori Listesi')


@push('css')
@endpush


@section('body')
    <div class="card">
        <div class="card-body">
            <h6 class="card-title">Kategori Listesi</h6>
            <div class="table-responsive pt-3">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Kategori Adi</th>
                            <th>Slug</th>
                            <th>Durum</th>
                            <th>Islemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>
                                <td>{{ $category->slug }}</td>
                                <td>
                                    @if ($category->status)
                                        <a href="javascript:void(0)" class="btn btn-inverse-success btn-change-status"
                                            data-id="{{ $category->id }}">Aktif</a>
                                    @else
                                        <a href="javascript:void(0)" class="btn btn-inverse-danger btn-change-status"
                                            data-id="{{ $category->id }}">Pasif</a>
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
                    {{ $categories->links() }}
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

                if (element.classList.contains('btn-delete-category')) {
                    let dataID = element.getAttribute('data-id');
                    let dataName = element.getAttribute('data-name');
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

                            // toastr.success('Kategori basariyla silindi!');
                        } else if (result.isDenied) {
                            Swal.fire("Herhangi bir islem gerceklestirilmedi!", "", "info");
                        }
                    });
                }
            });
        });
    </script>
@endpush
