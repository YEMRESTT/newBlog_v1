@extends('posts.layoutposts')

{{-- Sayfa başlığı (tarayıcı sekmesi) --}}
@section('title', $post->title . ' - Blog')

{{-- Ana sayfa başlığı --}}
@section('main-title', $post->title)

{{-- Sayfa alt başlığı --}}
@section('subtitle', 'Yazar: ' . $post->user->name . ' | ' . $post->created_at->format('d.m.Y'))

{{-- Ana içerik alanı --}}
@section('content')
    @can('devamını oku')

        {{-- Yazı meta bilgileri - Updated with modern styling --}}
        <div class="d-flex flex-wrap align-items-center gap-3 mb-4 pb-3 border-bottom">
        <span class="badge bg-light text-dark border px-3 py-2">
            <i class="bi bi-person-circle me-2"></i>
            {{ $post->user->name }}
        </span>
            <span class="badge bg-light text-dark border px-3 py-2">
            <i class="bi bi-calendar-event me-2"></i>
            {{ $post->created_at->format('d.m.Y') }}
        </span>
            <span class="badge bg-light text-dark border px-3 py-2">
            <i class="bi bi-eye me-2"></i>
            {{ $post->read_count }} okunma
        </span>
        </div>

        {{-- Yazı görseli - Updated with click-to-zoom functionality --}}
        @if($post->image)
            <div class="mb-5">
                <div class="post-image-container rounded-4 overflow-hidden shadow-sm position-relative"
                     style="cursor: pointer;"
                     onclick="openImageModal()">
                    <img src="{{ asset('storage/' . $post->image) }}"
                         alt="{{ $post->title }}"
                         class="post-detail-image"
                         id="postImage">
                    {{-- Zoom indicator overlay --}}
                    <div class="zoom-indicator position-absolute top-50 start-50 translate-middle">
                        <div class="bg-dark bg-opacity-75 text-white px-3 py-2 rounded-pill">
                            <i class="bi bi-zoom-in me-2"></i>
                            Büyütmek için tıklayın
                        </div>
                    </div>
                </div>
            </div>

            {{-- Image Modal - Click to zoom fullscreen --}}
            <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content bg-transparent border-0">
                        <div class="modal-body p-0 position-relative">
                            {{-- Close button --}}
                            <button type="button"
                                    class="btn-close btn-close-white position-absolute top-0 end-0 m-3 shadow-lg"
                                    data-bs-dismiss="modal"
                                    aria-label="Close"
                                    style="z-index: 10; background-size: 1.5rem; width: 2rem; height: 2rem;">
                            </button>
                            {{-- Zoomed image --}}
                            <img src="{{ asset('storage/' . $post->image) }}"
                                 alt="{{ $post->title }}"
                                 class="img-fluid w-100 h-auto rounded-4 shadow-lg"
                                 style="max-height: 90vh; object-fit: contain;">
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Yazı içeriği - Updated with better typography --}}
        <div class="mb-5">
            <div class="post-content">
                {!! nl2br(e($post->content)) !!}
            </div>
        </div>

        <hr class="my-5">

        {{-- Yorumlar bölümü - Updated with modern card design --}}
        @if($post->comments->count() > 0)
            <div class="mb-5">
                <h5 class="mb-4 fw-bold d-flex align-items-center">
                    <i class="bi bi-chat-dots me-2 text-primary"></i>
                    Yorumlar
                    <span class="badge bg-primary ms-2 rounded-pill">{{ $post->comments->count() }}</span>
                </h5>

                <div class="comments-wrapper">
                    @foreach($post->comments as $comment)
                        <div class="comment-card border-start border-primary border-3 ps-4 py-3 mb-3 bg-light rounded-end shadow-sm">
                            <div class="d-flex align-items-center mb-2">
                                <div class="comment-avatar rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3"
                                     style="width: 40px; height: 40px; font-weight: 600;">
                                    {{ strtoupper(substr($comment->name, 0, 1)) }}
                                </div>
                                <div>
                                    <strong class="text-dark d-block">{{ $comment->name }}</strong>
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>
                                        {{ $comment->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                            <p class="mb-0 text-dark lh-lg ps-5">{{ $comment->comment }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Etkileşim butonları - Updated with modern button styling --}}
        <div class="d-flex gap-2 justify-content-between align-items-center flex-wrap pt-4 border-top">
            <a href="{{ route('home') }}" class="btn btn-outline-secondary rounded-pill px-4 hover-lift">
                <i class="bi bi-arrow-left me-2"></i>
                Geri Dön
            </a>

            <div class="d-flex gap-2 flex-wrap">
                @can('yorum ekle')
                    <a href="{{ route('comments.create', $post->id) }}" class="btn btn-primary rounded-pill px-4 hover-lift">
                        <i class="bi bi-chat-left-text me-2"></i>
                        Yorum Yap
                    </a>
                @endcan

                @can('düzenle')
                    <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-warning rounded-pill px-4 hover-lift">
                        <i class="bi bi-pencil me-2"></i>
                        Düzenle
                    </a>
                @endcan

                @can('sil')
                    <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="btn btn-danger rounded-pill px-4 hover-lift"
                                onclick="return confirm('Bu yazıyı silmek istediğinize emin misiniz?')">
                            <i class="bi bi-trash me-2"></i>
                            Sil
                        </button>
                    </form>
                @endcan
            </div>
        </div>

        {{-- Modern styling with zoom functionality --}}
        <style>
            /* Post image container with click-to-zoom cursor */
            .post-image-container {
                display: flex;
                align-items: center;
                justify-content: center;
                background: #f8f9fa;
                max-height: 600px;
                transition: all 0.4s ease;
            }

            .post-image-container:hover {
                box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15) !important;
            }

            /* Image maintains original proportions with max constraints */
            .post-detail-image {
                width: 100%;
                height: auto;
                max-height: 600px;
                object-fit: contain;
                transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .post-image-container:hover .post-detail-image {
                transform: scale(1.02);
            }

            /* Zoom indicator - shows on hover */
            .zoom-indicator {
                opacity: 0;
                transition: opacity 0.3s ease;
                pointer-events: none;
                z-index: 5;
            }

            .post-image-container:hover .zoom-indicator {
                opacity: 1;
            }

            /* Modal backdrop customization */
            .modal-backdrop {
                background-color: rgba(0, 0, 0, 0.9);
            }

            /* Modal content animations */
            .modal.fade .modal-dialog {
                transition: transform 0.4s ease-out;
            }

            .modal.show .modal-dialog {
                transform: scale(1);
            }

            /* Close button enhancement */
            .btn-close-white {
                filter: brightness(0) invert(1);
                opacity: 0.8;
                transition: all 0.3s ease;
            }

            .btn-close-white:hover {
                opacity: 1;
                transform: rotate(90deg) scale(1.1);
            }

            /* Post content with enhanced typography */
            .post-content {
                font-size: 1.1rem;
                line-height: 1.8;
                color: #2c3e50;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            }

            .post-content p {
                margin-bottom: 1.5rem;
            }

            /* Comment card hover effect */
            .comment-card {
                transition: all 0.3s ease;
            }

            .comment-card:hover {
                background-color: #e7f1ff !important;
                transform: translateX(5px);
            }

            /* Comment avatar animation */
            .comment-avatar {
                transition: all 0.3s ease;
            }

            .comment-card:hover .comment-avatar {
                transform: scale(1.1) rotate(5deg);
            }

            /* Button hover lift effect */
            .hover-lift {
                transition: all 0.3s ease;
            }

            .hover-lift:hover {
                transform: translateY(-2px);
                box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
            }

            /* Modern border radius */
            .rounded-4 {
                border-radius: 1rem !important;
            }

            /* Responsive adjustments */
            @media (max-width: 768px) {
                .post-content {
                    font-size: 1rem;
                }

                .post-image-container {
                    max-height: 400px;
                }

                .post-detail-image {
                    max-height: 400px;
                }

                .zoom-indicator {
                    font-size: 0.85rem;
                }
            }

            /* Meta badges styling */
            .badge.bg-light {
                font-weight: 500;
                font-size: 0.85rem;
            }
        </style>

        {{-- JavaScript for image zoom modal --}}
        <script>
            // Open image modal on click
            function openImageModal() {
                const modal = new bootstrap.Modal(document.getElementById('imageModal'));
                modal.show();
            }

            // Close modal on background click
            document.addEventListener('DOMContentLoaded', function() {
                const imageModal = document.getElementById('imageModal');
                if (imageModal) {
                    imageModal.addEventListener('click', function(e) {
                        if (e.target === this) {
                            bootstrap.Modal.getInstance(this).hide();
                        }
                    });
                }
            });

            // Keyboard navigation - ESC to close
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const modalElement = document.getElementById('imageModal');
                    const modalInstance = bootstrap.Modal.getInstance(modalElement);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                }
            });
        </script>
    @endcan
@endsection
