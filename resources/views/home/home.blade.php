@extends('posts.layoutposts')

{{-- Sayfa başlığı (tarayıcı sekmesi) --}}
@section('title', 'Ana Sayfa - Blog')

{{-- Ana sayfa başlığı --}}
@section('main-title', 'Blog Yazıları')

{{-- Sayfa alt başlığı --}}
@section('subtitle', 'Son paylaşılan yazılar aşağıda listelenmiştir')

{{-- Ana içerik alanı --}}
@section('content')

    {{-- Üst işlem çubuğu - Updated with modern spacing --}}
    <div class="d-flex justify-content-between align-items-center mb-5">
        {{-- Toplam yazı sayısı --}}
        <div>
            <span class="badge bg-primary fs-6 px-4 py-2 rounded-pill shadow-sm">
                <i class="bi bi-file-text-fill me-2"></i>
                {{ $posts->count() }} Yazı
            </span>
        </div>

        {{-- Giriş/Çıkış butonları --}}
        <div>
            @guest
                {{-- Giriş yapmamış kullanıcı için --}}
                <a href="{{ route('login.form') }}" class="btn btn-success rounded-pill px-4 shadow-sm hover-lift">
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    Giriş Yap
                </a>
            @endguest

            @auth
                {{-- Giriş yapmış kullanıcı için --}}
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger rounded-pill px-4 shadow-sm hover-lift">
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
                    {{-- Blog yazısı kartı - Updated with modern card styling and side-by-side layout --}}
                    <div class="card border-0 shadow-sm hover-card rounded-4 overflow-hidden">
                        <div class="card-body p-4 p-lg-5">

                            {{-- Row layout: Content on left, Image on right --}}
                            <div class="row g-4 align-items-start">

                                {{-- Content column - Takes remaining space --}}
                                <div class="@if($post->image) col-lg-8 @else col-12 @endif">

                                    {{-- Yazı başlığı - Updated with modern typography --}}
                                    <h3 class="card-title mb-3 fw-bold fs-4">
                                        <a href="{{ route('home.show', $post->id) }}"
                                           class="text-decoration-none text-dark post-title-link">
                                            {{ $post->title }}
                                        </a>
                                    </h3>

                                    {{-- Yazar ve tarih bilgileri - Updated with better spacing and icons --}}
                                    <div class="d-flex flex-wrap align-items-center gap-3 mb-4 text-muted small">
                                        <span class="d-flex align-items-center">
                                            <i class="bi bi-person-circle me-2 fs-6"></i>
                                            <strong class="text-dark">{{ $post->user->name }}</strong>
                                        </span>
                                        <span class="d-flex align-items-center">
                                            <i class="bi bi-calendar-event me-2"></i>
                                            {{ $post->created_at->format('d.m.Y') }}
                                        </span>
                                        <span class="d-flex align-items-center">
                                            <i class="bi bi-eye me-2"></i>
                                            {{ $post->read_count }} okunma
                                        </span>
                                    </div>

                                    {{-- Yazı önizleme içeriği - Updated with better typography --}}
                                    <p class="card-text text-muted mb-4 lh-lg">
                                        {{ Str::limit($post->content, 200) }}
                                    </p>

                                    {{-- Devamını oku butonu - Updated with modern button style --}}
                                    @can('devamını oku')
                                        <a href="{{ route('home.show', $post->id) }}"
                                           class="btn btn-primary rounded-pill px-4 py-2 shadow-sm hover-lift">
                                            <i class="bi bi-arrow-right-circle me-2"></i>
                                            Devamını Oku
                                        </a>
                                    @endcan
                                </div>

                                {{-- Image column - Shows on right side with original proportions --}}
                                @if($post->image)
                                    <div class="col-lg-4">
                                        <div class="post-image-wrapper rounded-3 overflow-hidden shadow-sm">
                                            <img src="{{ asset('storage/' . $post->image) }}"
                                                 alt="{{ $post->title }}"
                                                 class="post-image w-100 h-auto">
                                        </div>
                                    </div>
                                @endif

                            </div>

                        </div>
                    </div>
                @endcan
            </div>
        @empty
            {{-- Yazı bulunamadığında gösterilecek mesaj - Updated with modern empty state --}}
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-body text-center py-5 px-4">
                        <div class="empty-state-icon mb-4">
                            <i class="bi bi-inbox fs-1 text-muted d-block" style="font-size: 5rem; opacity: 0.3;"></i>
                        </div>
                        <h4 class="text-dark fw-semibold mb-3">Henüz hiç yazı paylaşılmamış</h4>
                        <p class="text-muted mb-0">İlk yazıyı paylaşmak için admin paneline gidin.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Sayfalama (pagination) - Updated with better spacing --}}
    @if(method_exists($posts, 'links'))
        <div class="d-flex justify-content-center mt-5 mb-4">
            {{ $posts->links() }}
        </div>
    @endif

    {{-- Modern CSS animations and styling --}}
    <style>
        /* Smooth card hover animation with lift effect */
        .hover-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hover-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12) !important;
        }

        /* Post title link hover effect with smooth color transition */
        .post-title-link {
            transition: color 0.3s ease, transform 0.2s ease;
            display: inline-block;
        }

        .post-title-link:hover {
            color: #0d6efd !important;
            transform: translateX(4px);
        }

        /* Image wrapper with subtle hover effect */
        .post-image-wrapper {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            max-height: 350px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
        }

        /* Image maintains original aspect ratio and scales smoothly */
        .post-image {
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
            object-fit: contain;
            max-height: 350px;
        }

        .hover-card:hover .post-image {
            transform: scale(1.05);
        }

        .hover-card:hover .post-image-wrapper {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        /* Button lift effect on hover */
        .hover-lift {
            transition: all 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15) !important;
        }

        /* Empty state icon animation */
        .empty-state-icon i {
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 0.5; }
        }

        /* Responsive adjustments */
        @media (max-width: 991px) {
            /* Stack layout on mobile - image goes below content */
            .post-image-wrapper {
                max-height: 300px;
            }

            .card-title {
                font-size: 1.25rem !important;
            }
        }

        @media (max-width: 768px) {
            .card-body.p-lg-5 {
                padding: 1.5rem !important;
            }
        }

        /* Smooth border radius for modern look */
        .rounded-4 {
            border-radius: 1.5rem !important;
        }
    </style>

@endsection
