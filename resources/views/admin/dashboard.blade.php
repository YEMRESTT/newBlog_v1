@extends('posts.layoutposts')

@section('title', 'Dashboard')

@section('content')
    <div class="container mt-4">
        <h2>Hoş geldin, {{ Auth::user()->name }} 👋</h2>
        <p>Blog sistemine giriş yaptın. Buradan içeriklerini yönetebilirsin.</p>

        <div class="mt-4">
            <a href="{{ route('posts.index') }}" class="btn btn-primary">Tüm Yazılarımı Gör</a>
            <a href="{{ route('posts.create') }}" class="btn btn-success">Yeni Yazı Ekle</a>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button class="btn btn-danger">Çıkış Yap</button>
            </form>
        </div>
    </div>
@endsection
