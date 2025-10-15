@extends('posts.layoutposts')

@section('title', 'Ana Sayfa')
@section('main-title', 'Blog Yazıları')
@section('subtitle', 'Son paylaşılan yazılar aşağıda listelenmiştir.')

@section('content')

    <div class="container" >

        @foreach ($posts as $post)
            <div style="border-bottom: 35px solid #ddd; margin-bottom: 20px;">
                <h2>{{ $post->title }}</h2>
                <p><small>Yazan: {{ $post->user->name }} | {{ $post->created_at->format('d.m.Y') }}</small></p>
                <p>{{ Str::limit($post->content, 150) }}</p>
                <a href="{{ route('home.show', $post->id) }}">Yazının tamamını oku →</a>



                @if($post->comments->count() > 0)

                    <div style="margin-top: 15px;">
                        <h5>Yorumlar:</h5>

                        @foreach($post->comments as $comment)

                            <div style="margin-left: 20px; border-left: 3px solid #ccc; padding-left: 10px; margin-bottom: 10px;">
                                <strong>{{ $comment->name }}</strong> ({{ $comment->email }})<br>
                                <p>{{ $comment->comment }}</p>
                            </div>

                        @endforeach

                    </div>

                @endif
                <a href="{{ route('comments.create', $post->id) }}" class="btn btn-outline-secondary mt-2">Yorum Ekle</a>

            </div>


        @endforeach

            <div class="container-fluid d-flex">


                {{-- ms-auto sağa yaslamak için çalışır ama sadece d-flex içinde --}}
                <div class="ms-auto">
                    <form action="{{ route('login.form') }}" method="GET" style="display: inline;">
                        @csrf
                        <button type="submit"  class="btn rounded-pill btn-danger">Giriş yap</button>

                        </button>
                    </form>
                </div>
            </div>

    </div>
@endsection
