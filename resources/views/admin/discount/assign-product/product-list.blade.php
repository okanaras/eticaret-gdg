@extends('layouts.admin')


@section('title', 'Indirimlu Urun Listesi')


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
                    <x-filter-form :filters="$filters" custom-class="col-md-3"
                        action="{{ route('admin.discount.show-products-list', $discount->id) }}" />
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
                                        'text-primary fw-bolder' => request('order_by') == 'product_id',
                                    ]) data-order="product_id">
                                        Indirimli Urun {!! request('order_by') == 'product_id' && request('order_direction') === 'asc'
                                            ? '<i class="size-14" data-feather="arrow-down-circle"></i>'
                                            : (request('order_by') == 'product_id' && request('order_direction') === 'desc'
                                                ? '<i class="size-14" data-feather="arrow-up-circle"></i>'
                                                : '') !!}
                                    </th>

                                    <th @class([
                                        'order-by',
                                        'text-primary fw-bolder' => request('order_by') == 'final_price',
                                    ]) data-order="final_price">
                                        Urun Fiyati {!! request('order_by') == 'final_price' && request('order_direction') === 'asc'
                                            ? '<i class="size-14" data-feather="arrow-down-circle"></i>'
                                            : (request('order_by') == 'final_price' && request('order_direction') === 'desc'
                                                ? '<i class="size-14" data-feather="arrow-up-circle"></i>'
                                                : '') !!}
                                    </th>

                                    <th>Islemler</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($discounts as $item)
                                    <tr>
                                        <td>{{ $item->pId }}</td>
                                        <td>{{ $item->pName }}</td>
                                        <td>{{ number_format($item->final_price, 2, thousands_separator: '') }}</td>
                                        <td>
                                            <a href="javascript:void(0)">
                                                <i data-feather="trash" class="text-danger btn-delete-discount"
                                                    data-discount-id="{{ $discount->id }}"
                                                    data-product-id="{{ $item->pId }}" data-name="{{ $item->pName }}">
                                                </i>
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
                            {{-- {{ $discounts->WithQueryString()->links() }} --}}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('js')
@endpush
