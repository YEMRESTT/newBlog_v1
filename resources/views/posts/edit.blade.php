@extends('posts.layoutposts')

{{-- Sayfa başlığı (tarayıcı sekmesi) --}}
@section('title', 'Yazıyı Düzenle - Admin Panel')

{{-- Ana sayfa başlığı --}}
@section('main-title', 'Yazıyı Düzenle')

{{-- Sayfa alt başlığı --}}
@section('subtitle', 'Blog yazınızı güncelleyin')

{{-- Ana içerik alanı --}}
@section('content')

    {{-- Başarı mesajı bildirimi --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Hata mesajları --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Hata!</strong> Lütfen aşağıdaki hataları düzeltin:
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @can('edit post')
        {{-- Yazı düzenleme formu kartı --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h5 class="mb-0">
                    <i class="bi bi-pencil-square me-2"></i>
                    Yazı Bilgileri
                </h5>
            </div>

            <div class="card-body p-4">
                {{-- Bilgilendirme kutusu --}}
                <div class="alert alert-info d-flex align-items-start mb-4" role="alert">
                    <i class="bi bi-info-circle-fill fs-5 me-3 mt-1"></i>
                    <div>
                        <strong>Bilgi:</strong>
                        Yazıyı güncelledikten sonra değişiklikler anında yayınlanacaktır. Lütfen kontrol ederek kaydedin.
                    </div>
                </div>

                {{-- Mevcut yazı bilgileri --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <p class="mb-1 text-muted small">Yazar</p>
                                <p class="mb-0 fw-bold">
                                    <i class="bi bi-person-circle text-primary me-1"></i>
                                    {{ $post->user->name }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <p class="mb-1 text-muted small">Oluşturulma</p>
                                <p class="mb-0 fw-bold">
                                    <i class="bi bi-calendar-plus text-success me-1"></i>
                                    {{ $post->created_at->format('d.m.Y') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <p class="mb-1 text-muted small">Okunma</p>
                                <p class="mb-0 fw-bold">
                                    <i class="bi bi-eye text-info me-1"></i>
                                    {{ $post->read_count }} kez
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Yazı düzenleme formu --}}
                <form method="POST" action="{{ route('posts.update', $post) }}" id="editPostForm">
                    @csrf
                    @method('PUT')

                    {{-- Başlık alanı --}}
                    <div class="mb-4">
                        <label for="title" class="form-label">
                            <i class="bi bi-cursor-text me-1"></i>
                            Yazı Başlığı <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               name="title"
                               id="title"
                               class="form-control form-control-lg @error('title') is-invalid @enderror"
                               value="{{ old('title', $post->title) }}"
                               placeholder="Yazınızın başlığını girin..."
                               required
                               maxlength="255">
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            Maksimum 255 karakter
                        </div>
                    </div>

                    {{-- İçerik alanı --}}
                    <div class="mb-4">
                        <label for="content" class="form-label">
                            <i class="bi bi-file-text me-1"></i>
                            Yazı İçeriği <span class="text-danger">*</span>
                        </label>
                        <textarea name="content"
                                  id="content"
                                  rows="12"
                                  class="form-control form-control-lg @error('content') is-invalid @enderror"
                                  placeholder="Yazınızın içeriğini buraya yazın..."
                                  required>{{ old('content', $post->content) }}</textarea>
                        @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text d-flex justify-content-between">
                            <span>
                                <i class="bi bi-pencil me-1"></i>
                                Minimum 10 karakter gereklidir
                            </span>
                            <span id="charCount" class="text-muted">
                                <i class="bi bi-calculator me-1"></i>
                                <span id="currentCount">{{ strlen($post->content) }}</span> karakter
                            </span>
                        </div>
                    </div>

                    {{-- Form butonları --}}
                    <div class="d-flex gap-2 justify-content-between align-items-center pt-3 border-top">
                        <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>
                            Geri Dön
                        </a>

                        <div class="d-flex gap-2">
                            <button type="reset" class="btn btn-outline-warning">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>
                                Sıfırla
                            </button>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-save-fill me-2"></i>
                                Değişiklikleri Kaydet
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- İpuçları kartı --}}
        <div class="card border-success mt-4">
            <div class="card-header bg-success bg-opacity-10 border-success">
                <h6 class="mb-0 text-success">
                    <i class="bi bi-lightbulb-fill me-2"></i>
                    İpuçları
                </h6>
            </div>
            <div class="card-body">
                <ul class="mb-0">
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Başlık kısa, öz ve açıklayıcı olmalıdır
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        İçeriği paragraf paragraf yazarak okunabilirliği artırın
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Yazım kurallarına dikkat edin
                    </li>
                    <li class="mb-0">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Kaydetmeden önce yazınızı kontrol edin
                    </li>
                </ul>
            </div>
        </div>

    @else
        {{-- Yetki yoksa uyarı mesajı --}}
        <div class="card border-danger">
            <div class="card-body text-center py-5">
                <i class="bi bi-lock-fill fs-1 text-danger d-block mb-3"></i>
                <h4 class="text-danger">Düzenleme Yetkiniz Yok</h4>
                <p class="text-muted mb-4">Bu yazıyı düzenlemek için yetkiniz bulunmuyor.</p>
                <a href="{{ route('posts.index') }}" class="btn btn-primary">
                    <i class="bi bi-arrow-left me-2"></i>
                    Yazılara Dön
                </a>
            </div>
        </div>
    @endcan

    {{-- Özel stil ve script tanımlamaları --}}
    <style>
        /* Form input focus efekti */
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        /* Textarea resize kontrolü */
        textarea.form-control {
            resize: vertical;
            min-height: 200px;
        }

        /* Buton hover efektleri */
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
        }

        .btn-outline-warning:hover {
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }

        /* Bilgi kartları hover efekti */
        .card.bg-light {
            transition: all 0.3s ease;
        }

        .card.bg-light:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>

    <script>
        // Karakter sayacı
        document.addEventListener('DOMContentLoaded', function() {
            const contentTextarea = document.getElementById('content');
            const currentCountSpan = document.getElementById('currentCount');

            if (contentTextarea && currentCountSpan) {
                contentTextarea.addEventListener('input', function() {
                    currentCountSpan.textContent = this.value.length;
                });
            }

            // Form gönderilmeden önce onay
            const editForm = document.getElementById('editPostForm');
            if (editForm) {
                editForm.addEventListener('submit', function(e) {
                    const title = document.getElementById('title').value;
                    const content = document.getElementById('content').value;

                    if (title.trim().length < 3) {
                        e.preventDefault();
                        alert('Başlık en az 3 karakter olmalıdır!');
                        return false;
                    }

                    if (content.trim().length < 10) {
                        e.preventDefault();
                        alert('İçerik en az 10 karakter olmalıdır!');
                        return false;
                    }
                });
            }
        });
    </script>

@endsection
