@extends('layouts.front')

@section('title', 'Siparislerim')

@push('css')
@endpush

@section('body')

    <main>
        <div class="container">
            <div class="row">
                <div class="col-md-10 mx-auto p-5 shadow my-orders">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">Siparis Numarasi</th>
                                    <th scope="col">Siparis Durumu</th>
                                    <th scope="col">Toplam Fiyat</th>
                                    <th scope="col">Siparis Detaylari</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#10</td>
                                    <td>
                                        <span class="text-warning"><i class="bi bi-arrow-repeat"></i>
                                            Hazirlaniyor</span>
                                    </td>
                                    <td>199,90 TL</td>
                                    <td>
                                        <a href="" class="btn btn-warning text-white" title="goruntule">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#11</td>
                                    <td>
                                        <span class="text-success"><i class="bi bi-check"></i>
                                            Teslim Edildi</span>
                                    </td>
                                    <td>199,90 TL</td>
                                    <td>
                                        <a href="" class="btn btn-warning text-white" title="goruntule">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#12</td>
                                    <td>
                                        <span class="text-primary"><i class="bi bi-check-all"></i>
                                            Onaylandi</span>
                                    </td>
                                    <td>199,90 TL</td>
                                    <td>
                                        <a href="" class="btn btn-warning text-white" title="goruntule">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection

@push('js')
@endpush
