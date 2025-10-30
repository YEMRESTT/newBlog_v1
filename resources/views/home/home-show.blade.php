@extends('posts.layoutposts')

{{-- Sayfa başlığı (tarayıcı sekmesi) --}}
@section('title', $post->title . ' - Blog')

{{-- Ana sayfa başlığı --}}
@section('main-title', $post->title)

{{-- Sayfa alt başlığı --}}
@section('subtitle', 'Yazar: ' . $post->user->name . ' | ' . $post->created_at->format('d.m.Y'))

{{-- Ana içerik alanı --}}
@section('content')

    {{-- Yazı meta bilgileri (kompakt) --}}
    <div class="d-flex flex-wrap gap-2 mb-3">
        <small class="text-muted">
            <i class="bi bi-person-circle me-1"></i>
            {{ $post->user->name }}
        </small>
        <small class="text-muted">
            <i class="bi bi-calendar-event me-1"></i>
            {{ $post->created_at->format('d.m.Y') }}
        </small>
        <small class="text-muted">
            <i class="bi bi-eye me-1"></i>
            {{ $post->read_count }} okunma
        </small>
    </div>

    {{-- Yazı görseli (varsa) - Daha küçük --}}
    @if($post->image)
        <div class="mb-4">
            <img src="{{ asset('storage/' . $post->image) }}"
                 alt="{{ $post->title }}"
                 class="img-fluid rounded"
                 style="max-height: 300px; width: 100%; object-fit: cover;">
        </div>
    @endif

    {{-- Yazı içeriği (sade) --}}
    <div class="mb-4">
        <div class="post-content">
            {!! nl2br(e($post->content)) !!}
        </div>
    </div>

    <hr class="my-4">

    {{-- Yorumlar bölümü (sade) --}}
    @can('see comments')
        @if($post->comments->count() > 0)
            <div class="mb-4">
                <h5 class="mb-3">
                    <i class="bi bi-chat-dots me-2"></i>
                    Yorumlar ({{ $post->comments->count() }})
                </h5>

                @foreach($post->comments as $comment)
                    <div class="border-start border-primary border-2 ps-3 py-2 mb-3">
                        <div class="mb-1">
                            <strong>{{ $comment->name }}</strong>
                            <small class="text-muted ms-2">{{ $comment->created_at->diffForHumans() }}</small>
                        </div>
                        <p class="mb-0 text-muted">{{ $comment->comment }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    @endcan

    {{-- Etkileşim butonları (sadece gerekli olanlar) --}}
    <div class="d-flex gap-2 justify-content-between align-items-center flex-wrap">
        <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i>
            Geri
        </a>

        <div class="d-flex gap-2">
            @can('add comment')
                <a href="{{ route('comments.create', $post->id) }}" class="btn btn-primary btn-sm">
                    <i class="bi bi-chat-left-text me-1"></i>
                    Yorum Yap
                </a>
            @endcan

            @can('edit post')
                <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning btn-sm">
                    <i class="bi bi-pencil me-1"></i>
                    Düzenle
                </a>
            @endcan

            @can('delete post')
                <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Bu yazıyı silmek istediğinize emin misiniz?')">
                        <i class="bi bi-trash me-1"></i>
                        Sil
                    </button>
                </form>
            @endcan
        </div>
    </div>

    {{-- Minimal stil tanımlamaları --}}
    <style>
        /* Post content basit stil */
        .post-content {
            font-size: 1rem;
            line-height: 1.7;
            color: #333;
        }

        .post-content p {
            margin-bottom: 1rem;
        }
    </style>

@endsection
