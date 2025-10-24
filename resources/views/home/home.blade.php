@extends('posts.layoutposts')

@section('title', 'Ana Sayfa')
@section('main-title', 'Blog Yazıları')
@section('subtitle', 'Son paylaşılan yazılar aşağıda listelenmiştir.')

@section('content')

    <div class="container py-4">

        @foreach ($posts as $post)
            <div class="row justify-content-center mb-9">
                <div class="col-12 col-md-10 col-lg-8">
                    <div class="card shadow-lg border-0 rounded-4 p-4 bg-light hover-shadow">

                        <h2>{{ $post->title }}</h2>
                        <p><small>Yazan: {{ $post->user->name }} | {{ $post->created_at->format('d.m.Y') }}</small></p>
                        <p>{{ Str::limit($post->content, 4) }}</p>
                        <p>Okunma sayısı: {{ $post->read_count }}</p>
                        <a href="{{ route('home.show', $post->id) }}">Yazının tamamını oku →</a>

                        @if($post->comments->count() > 0)
                            <div class="mt-3">
                                <h5>Yorumlar:</h5>
                                @foreach($post->comments as $comment)
                                    <div class="comment-box">
                                        <strong>{{ $comment->name }}</strong> ({{ $comment->email }})<br>
                                        <p>{{ $comment->comment }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <a href="{{ route('comments.create', $post->id) }}" class="btn btn-outline-secondary mt-2">Yorum Ekle</a>

                    </div>
                </div>
            </div>
        @endforeach

        <div class="container-fluid d-flex">
            {{-- Eğer kullanıcı giriş yapmamışsa GİRİŞ YAP butonu göster --}}
            @guest
                <form action="{{ route('login.form') }}" method="GET" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn rounded-pill btn-danger">Giriş yap</button>
                </form>
            @endguest

            {{-- Eğer kullanıcı giriş yapmışsa ÇIKIŞ YAP butonu göster --}}
            @auth
                <div class="ms-auto">
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn rounded-pill btn-danger">Çıkış yap</button>
                    </form>
                </div>
            @endauth
        </div>

    </div>

@endsection
