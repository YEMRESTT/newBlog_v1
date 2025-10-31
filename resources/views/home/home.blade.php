@extends('posts.layoutposts')

{{-- Sayfa başlığı (tarayıcı sekmesi) --}}
@section('title', 'Ana Sayfa - Blog')

{{-- Ana sayfa başlığı --}}
@section('main-title', 'Blog Yazıları')

{{-- Sayfa alt başlığı --}}
@section('subtitle', 'Son paylaşılan yazılar aşağıda listelenmiştir')

{{-- Ana içerik alanı --}}
@section('content')

    {{-- Üst işlem çubuğu --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        {{-- Toplam yazı sayısı --}}
        <div>
            <span class="badge bg-primary fs-6">
                <i class="bi bi-file-text-fill me-2"></i>
                {{ $posts->count() }} Yazı
            </span>
        </div>

        {{-- Giriş/Çıkış butonları --}}
        <div>
            @guest
                {{-- Giriş yapmamış kullanıcı için --}}
                <a href="{{ route('login.form') }}" class="btn btn-success rounded-pill">
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    Giriş Yap
                </a>
            @endguest

            @auth
                {{-- Giriş yapmış kullanıcı için --}}
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger rounded-pill">
                        <i class="bi bi-box-arrow-right me-2"></i>
                        Çıkış Yap
                    </button>
                </form>
            @endauth
        </div>
    </div>

    {{-- Blog yazıları listesi --}}
    <div class="row g-4">
        @forelse ($posts as $post)
            <div class="col-12">
                @can('paylaşımlar paneli görüntüle')
                    {{-- Blog yazısı kartı --}}
                    <div class="card border-0 shadow-sm hover-card">
                        <div class="card-body p-4">
                            {{-- Yazı başlığı --}}
                            <h3 class="card-title mb-3">
                                <a href="{{ route('home.show', $post->id) }}" class="text-decoration-none text-dark">
                                    {{ $post->title }}
                                </a>
                            </h3>

                            {{-- Yazar ve tarih bilgileri --}}
                            <div class="d-flex align-items-center gap-3 mb-3 text-muted">
                                <span>
                                    <i class="bi bi-person-circle me-1"></i>
                                    <strong>{{ $post->user->name }}</strong>
                                </span>
                                <span>
                                    <i class="bi bi-calendar-event me-1"></i>
                                    {{ $post->created_at->format('d.m.Y') }}
                                </span>
                                <span>
                                    <i class="bi bi-eye me-1"></i>
                                    {{ $post->read_count }} okunma
                                </span>
                            </div>

                            {{-- Yazı önizleme içeriği --}}
                            <p class="card-text text-muted mb-3">
                                {{ Str::limit($post->content, 200) }}
                            </p>

                            {{-- Devamını oku butonu --}}
                            <a href="{{ route('home.show', $post->id) }}" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-right-circle me-1"></i>
                                Devamını Oku
                            </a>

                            <hr class="my-4">

                            {{-- Yorumlar bölümü --}}
                            @can('paylaşımlar paneli görüntüle')
                                @if($post->comments->count() > 0)
                                    <div class="comments-section">
                                        <h5 class="mb-3">
                                            <i class="bi bi-chat-dots me-2"></i>
                                            Yorumlar ({{ $post->comments->count() }})
                                        </h5>

                                        {{-- Yorum listesi --}}
                                        <div class="comments-list">
                                            @foreach($post->comments->take(3) as $comment)
                                                <div class="comment-item border-start border-primary border-3 ps-3 py-2 mb-3 bg-light rounded">
                                                    <div class="d-flex align-items-center gap-2 mb-2">
                                                        <div class="avatar-initial rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                             style="width: 30px; height: 30px; font-size: 0.75rem; font-weight: 600;">
                                                            {{ strtoupper(substr($comment->name, 0, 1)) }}
                                                        </div>
                                                        <div>
                                                            <strong class="text-dark">{{ $comment->name }}</strong>
                                                            <small class="text-muted d-block">{{ $comment->email }}</small>
                                                        </div>
                                                    </div>
                                                    <p class="mb-0 text-muted ps-4">{{ $comment->comment }}</p>
                                                </div>
                                            @endforeach

                                            {{-- Daha fazla yorum varsa bildirim --}}
                                            @if($post->comments->count() > 3)
                                                <p class="text-muted small mb-0">
                                                    <i class="bi bi-plus-circle me-1"></i>
                                                    ve {{ $post->comments->count() - 3 }} yorum daha...
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <div class="text-center text-muted py-3">
                                        <i class="bi bi-chat-square-text fs-3 d-block mb-2"></i>
                                        <p class="mb-0">Henüz yorum yapılmamış. İlk yorumu siz yapın!</p>
                                    </div>
                                @endif
                            @endcan

                            {{-- Yorum ekleme butonu --}}
                            @can('add comment')
                                <div class="mt-3">
                                    <a href="{{ route('comments.create', $post->id) }}" class="btn btn-outline-secondary">
                                        <i class="bi bi-chat-left-text me-1"></i>
                                        Yorum Ekle
                                    </a>
                                </div>
                            @endcan
                        </div>
                    </div>
                @endcan
            </div>
        @empty
            {{-- Yazı bulunamadığında gösterilecek mesaj --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-inbox fs-1 text-muted d-block mb-3"></i>
                        <h4 class="text-muted">Henüz hiç yazı paylaşılmamış</h4>
                        <p class="text-muted mb-0">İlk yazıyı paylaşmak için admin paneline gidin.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Sayfalama (pagination) - Eğer varsa --}}
    @if(method_exists($posts, 'links'))
        <div class="d-flex justify-content-center mt-5">
            {{ $posts->links() }}
        </div>
    @endif

    {{-- Özel hover efekti için CSS --}}
    <style>
        /* Kart hover animasyonu */
        .hover-card {
            transition: all 0.3s ease;
        }

        .hover-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15) !important;
        }

        /* Başlık hover efekti */
        .card-title a {
            transition: color 0.3s ease;
        }

        .card-title a:hover {
            color: #0d6efd !important;
        }

        /* Yorum item'ları için animasyon */
        .comment-item {
            transition: all 0.3s ease;
        }

        .comment-item:hover {
            background-color: #e7f1ff !important;
        }

        /* Avatar animasyonu */
        .avatar-initial {
            transition: all 0.3s ease;
        }

        .comment-item:hover .avatar-initial {
            transform: scale(1.1);
        }
    </style>

@endsection
