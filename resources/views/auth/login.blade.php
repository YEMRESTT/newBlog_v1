@extends('layout.accound')

@section('title')
    Giriş Yap
@endsection

@section('main-title')
    Giriş Yap
@endsection

@section('subtitle')
    Giriş yapmak için bilgilerinizi giriniz
@endsection

@section('contend')

    {{-- Hata mesajları --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Başarı mesajları --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form id="formAuthentication" class="mb-6" method="POST" action="{{route('login')}}">
        @csrf

        <div class="mb-6">
            <label for="email" class="form-label">Email </label>
            <input
                type="email"
                class="form-control"
                id="email"
                name="email"
                placeholder="Enter your email or username"
                autofocus
                required/>
        </div>

        <div class="mb-6 form-password-toggle">
            <label class="form-label" for="password">Password</label>
            <div class="input-group input-group-merge">
                <input
                    type="password"
                    id="password"
                    class="form-control"
                    name="password"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                    required/>
                <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
            </div>
        </div>

        <div class="mb-6">
            <button class="btn btn-primary d-grid w-100" type="submit">Giriş Yap</button>
        </div>
    </form>

    {{-- AYIRICI ÇİZGİ --}}
    <div class="divider my-6">
        <div class="divider-text">veya</div>
    </div>

    {{-- GOOGLE İLE GİRİŞ BUTONU --}}
    <div class="mb-6">
        <a href="{{ route('auth.google') }}" class="btn btn-outline-secondary d-grid w-100">
            <span class="d-flex align-items-center justify-content-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="me-2" viewBox="0 0 16 16">
                    <path d="M15.545 6.558a9.42 9.42 0 0 1 .139 1.626c0 2.434-.87 4.492-2.384 5.885h.002C11.978 15.292 10.158 16 8 16A8 8 0 1 1 8 0a7.689 7.689 0 0 1 5.352 2.082l-2.284 2.284A4.347 4.347 0 0 0 8 3.166c-2.087 0-3.86 1.408-4.492 3.304a4.792 4.792 0 0 0 0 3.063h.003c.635 1.893 2.405 3.301 4.492 3.301 1.078 0 2.004-.276 2.722-.764h-.003a3.702 3.702 0 0 0 1.599-2.431H8v-3.08h7.545z"/>
                </svg>
                Google ile Giriş Yap
            </span>
        </a>
    </div>

    <p class="text-center">
        <span>Hesabın yok mu?</span>
        <a href="{{ route('register.form') }}">
            <span>Kayıt Olun</span>
        </a>
    </p>

@endsection
