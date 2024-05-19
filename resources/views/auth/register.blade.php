@extends('layouts.auth')


@section('title', 'Kayit Ol')


@push('css')
@endpush


@section('body')
    <div class="auth-form-wrapper px-4 py-5">
        <a href="#" class="noble-ui-logo d-block mb-2">GDG<span>ETicaret</span></a>
        <h5 class="text-muted fw-normal mb-4">Hesap Olusturma</h5>
        <form class="forms-sample" action="{{ route('register') }}" method="POST" id="registerForm">
            @csrf
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="mb-3">
                <label for="name" class="form-label">Ad Soyad</label>
                {{-- css e @error('validateVerilenAd')@enderror ile dokunulabilinir
                value="{{ old('name') }}" ile eski degerini alabiliriz --}}
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                    placeholder="Ad Soyad" value="{{ old('name') }}">
                @error('name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email Adresi</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                    name="email" placeholder="Email" value="{{ old('email') }}">
                @error('email')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Parola</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                    name="password" placeholder="Parola">
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Parola Tekrari</label>
                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                    id="password_confirmation" name="password_confirmation" placeholder="Parola Tekrari">
                @error('password_confirmation')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="authCheck" name="remember">
                <label class="form-check-label" for="authCheck">
                    Beni Hatirla
                </label>
            </div>
            <div>
                <a href="javascript:void(0)" class="btn btn-primary text-white me-2 mb-2 mb-md-0" id="btnRegister">Kayit
                    Ol</a>
                <a href="{{ route('login.socialite', ['driver' => 'google']) }}"
                    class="btn btn-outline-primary btn-icon-text mb-2 mb-md-0">
                    <i class="mdi mdi-google"></i>
                    Google ile Kayit Ol
                </a>
                <a href="{{ route('login.socialite', ['driver' => 'github']) }}"
                    class="btn btn-outline-primary btn-icon-text mb-2 mb-md-0">
                    <i class="mdi mdi-github"></i>
                    Github ile Kayit Ol
                </a>
            </div>
            <a href="{{ route('login') }}" class="d-block mt-3 text-muted">Giris Yap</a>
        </form>
    </div>

@endsection


@push('js')
    <script src="{{ asset('assets/js/auth/register.js') }}"></script>
@endpush
