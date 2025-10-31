@extends('layout.accound')

@section('title')
    Kayıt Ol
@endsection

@section('main-title')
    Kayıt Ol
@endsection

@section('subtitle')
    Kayıt olmak için alttaki bilgileri doldurunuz.
@endsection


@section('contend')
    @if ($errors->any())

        <div style="color:red;">
            <ul>
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    <form id="formAuthentication"  class="mb-6" method="POST" action="{{route('register')}}">

        @csrf

        <div class="mb-6">
            <label for="name" class="form-label">Ad Soyad</label>
            <input
                type="text"
                class="form-control"
                id="name"
                name="name"
                placeholder="Enter your name"
                autofocus
                required/>
        </div>

        <div class="mb-6">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required/>
        </div>

        <div class="form-password-toggle">
            <label class="form-label" for="password">Şifre</label>
            <div class="input-group input-group-merge">
                <input
                    type="password"
                    id="password"
                    class="form-control"
                    name="password"
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                    aria-describedby="password"
                    required/>
                <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
            </div>
        </div>

        <div class="form-password-toggle">
            <label class="form-label" for="password">Şifre (Tekrar) </label>
            <div class="input-group input-group-merge">
                <input
                    type="password"
                    id="password_confirmation"
                    class="form-control"
                    name="password_confirmation"
                    placeholder="Şifrenizi tekrar giriniz"
                    required
                />

                <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
            </div>
        </div>

        <div class="my-7">

        </div>
        <button type="submit" class="btn btn-primary d-grid w-100">Kayıt Ol</button>
    </form>
    <p class="text-center">
        <span>Zaten bir hesabın var mı?</span>
        <a href="{{route('login.form')}}">
            <span>Giriş Yapın</span>
        </a>
    </p>

@endsection
