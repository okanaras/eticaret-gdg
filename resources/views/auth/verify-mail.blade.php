@extends('layouts.auth')


@section('title', 'Email Dogrulama')


@push('css')
@endpush


@section('body')
    <div class="auth-form-wrapper px-4 py-5">
        <a href="#" class="noble-ui-logo d-block mb-2">GDG<span>ETicaret</span></a>
        <h5 class="text-muted fw-normal mb-4">Hos geldiniz.</h5>
        <form class="forms-sample" action="{{ route('send-verify-mail') }}" method="POST" id="verifyMailForm">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email Adresi</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email"
                    value="{{ old('email') }}">
            </div>

            <div>
                <a href="javascript:void(0)" id="btnSendMail" class="btn btn-primary me-2 mb-2 mb-md-0 text-white">Dogrulama
                    Maili Gonder</a>
            </div>
            <a href="{{ route('register') }}" class="d-block mt-3 text-muted">Hesap Olustur</a>
        </form>
    </div>

@endsection


@push('js')
    <script src="{{ asset('assets/js/auth/verifySendMail.js') }}"></script>
@endpush
