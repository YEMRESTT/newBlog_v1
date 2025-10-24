@extends('posts.layoutposts')

@section('title', 'Yorum')
@section('main-title', 'Yorum Yaz')

@section('content')
    <div class="container mt-4">
        <h4>Yorum Ekle</h4>



        <form action="{{ route('comments.add') }}" method="POST">
            @csrf
            <input type="hidden" name="post_id" value="{{ $post_id }}">

            {{-- Kullanıcı giriş yaptıysa bilgileri otomatik doldur --}}
            @auth
                <div class="mb-3">
                    <input type="text" name="name"
                           class="form-control"
                           value="{{ Auth::user()->name }}"
                           readonly>
                </div>

                <div class="mb-3">
                    <input type="email" name="email"
                           class="form-control"
                           value="{{ Auth::user()->email }}"
                           readonly>
                </div>
            @else
                {{-- Giriş yapmayan kullanıcılar için manuel giriş --}}
                <div class="mb-3">
                    <input type="text" name="name" class="form-control" placeholder="İsminiz" required>
                </div>

                <div class="mb-3">
                    <input type="email" name="email" class="form-control" placeholder="E-posta adresiniz" required>
                </div>
            @endauth

            <div class="mb-3">
                <textarea name="comment" class="form-control" placeholder="Yorumunuz" rows="3" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Yorumu Gönder</button>
        </form>
    </div>
@endsection
