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

    <p class="text-center">
        <span>Hesabın yok mu?</span>
        <a href="{{ route('register.form') }}">
            <span>Kayıt Olun</span>
        </a>
    </p>

@endsection
