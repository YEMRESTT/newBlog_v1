@extends('posts.layoutposts')

{{-- Sayfa başlığı (tarayıcı sekmesi) --}}
@section('title', 'Yeni Yazı Ekle - Admin Panel')

{{-- Ana sayfa başlığı --}}
@section('main-title', 'Yeni Yazı Ekle')

{{-- Sayfa alt başlığı --}}
@section('subtitle', 'Blog için yeni bir yazı oluşturun')

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

    @can('yeni yazı ekle')
        {{-- Yeni yazı ekleme formu kartı --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h5 class="mb-0">
                    <i class="bi bi-plus-circle me-2"></i>
                    Yazı Bilgileri
                </h5>
            </div>

            <div class="card-body p-4">
                {{-- Bilgilendirme kutusu --}}
                <div class="alert alert-info d-flex align-items-start mb-4" role="alert">
                    <i class="bi bi-info-circle-fill fs-5 me-3 mt-1"></i>
                    <div>
                        <strong>Bilgi:</strong>
                        Yeni yazınız kaydedildikten sonra anında yayınlanacaktır. Görsel eklemek isteğe bağlıdır.
                    </div>
                </div>

                {{-- Yazar bilgisi kartı --}}
                @auth
                    <div class="card bg-light border-0 mb-4">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar-initial rounded-circle bg-primary text-white me-3 d-flex align-items-center justify-content-center"
                                     style="width: 45px; height: 45px; font-weight: 600; font-size: 1.25rem;">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="mb-0 text-muted small">Yazar</p>
                                    <p class="mb-0 fw-bold">
                                        <i class="bi bi-person-circle text-primary me-1"></i>
                                        {{ Auth::user()->name }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endauth

                {{-- Yeni yazı ekleme formu --}}
                <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" id="createPostForm">
                    @csrf

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
                               value="{{ old('title') }}"
                               placeholder="Örn: Laravel ile Modern Web Geliştirme"
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
                                  placeholder="Yazınızın içeriğini buraya yazın...&#10;&#10;İpucu: Paragraflar arası boşluk bırakarak okunabilirliği artırabilirsiniz."
                                  required>{{ old('content') }}</textarea>
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
                                <span id="currentCount">0</span> karakter
                            </span>
                        </div>
                    </div>

                    {{-- Görsel yükleme alanı --}}
                    @can('resim ekle')
                    <div class="mb-4">
                        <label for="image" class="form-label">
                            <i class="bi bi-image me-1"></i>
                            Yazı Görseli <span class="text-muted">(İsteğe bağlı)</span>
                        </label>
                        <input type="file"
                               name="image"
                               id="image"
                               class="form-control @error('image') is-invalid @enderror"
                               accept="image/*">
                        @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="bi bi-file-earmark-image me-1"></i>
                            Desteklenen formatlar: JPG, PNG, GIF - Maksimum boyut: 2MB
                        </div>

                        {{-- Görsel önizleme alanı --}}
                        <div id="imagePreview" class="mt-3" style="display: none;">
                            <div class="card border-primary">
                                <div class="card-header bg-primary bg-opacity-10 border-primary">
                                    <small class="text-primary">
                                        <i class="bi bi-eye me-1"></i>
                                        Görsel Önizleme
                                    </small>
                                </div>
                                <div class="card-body text-center p-3">
                                    <img id="previewImage" src="" alt="Önizleme" class="img-fluid rounded" style="max-height: 300px;">
                                </div>
                            </div>
                        </div>
                    </div>
                    @endcan

                    {{-- Form butonları --}}
                    <div class="d-flex gap-2 justify-content-between align-items-center pt-3 border-top">
                        <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>
                            Geri Dön
                        </a>

                        <div class="d-flex gap-2">
                            <button type="reset" class="btn btn-outline-warning" id="resetBtn">
                                <i class="bi bi-arrow-counterclockwise me-1"></i>
                                Formu Temizle
                            </button>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-save-fill me-2"></i>
                                Yazıyı Yayınla
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
                    Yazı Yazma İpuçları
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <ul class="mb-0">
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Başlık dikkat çekici ve açıklayıcı olmalı
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Paragraflar arası boşluk bırakın
                            </li>
                            <li class="mb-md-0">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Yazım kurallarına dikkat edin
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="mb-0">
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Görsel kullanımı okuyucu ilgisini artırır
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                İçeriği kısa ve öz tutun
                            </li>
                            <li class="mb-0">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Yayınlamadan önce kontrol edin
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    @else
        {{-- Yetki yoksa uyarı mesajı --}}
        <div class="card border-danger">
            <div class="card-body text-center py-5">
                <i class="bi bi-lock-fill fs-1 text-danger d-block mb-3"></i>
                <h4 class="text-danger">Yazı Oluşturma Yetkiniz Yok</h4>
                <p class="text-muted mb-4">Yeni yazı oluşturmak için yetkiniz bulunmuyor.</p>
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

        /* File input stil */
        input[type="file"].form-control:hover {
            border-color: #667eea;
            cursor: pointer;
        }

        /* Buton hover efektleri */
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
            transition: all 0.3s ease;
        }

        .btn-outline-warning:hover {
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }

        /* Görsel önizleme animasyonu */
        #imagePreview {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Avatar hover efekti */
        .avatar-initial {
            transition: all 0.3s ease;
        }

        .card.bg-light:hover .avatar-initial {
            transform: scale(1.1) rotate(5deg);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Karakter sayacı
            const contentTextarea = document.getElementById('content');
            const currentCountSpan = document.getElementById('currentCount');

            if (contentTextarea && currentCountSpan) {
                contentTextarea.addEventListener('input', function() {
                    currentCountSpan.textContent = this.value.length;
                });
            }

            // Görsel önizleme
            const imageInput = document.getElementById('image');
            const imagePreview = document.getElementById('imagePreview');
            const previewImage = document.getElementById('previewImage');

            if (imageInput) {
                imageInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];

                    if (file) {
                        // Dosya boyutu kontrolü (2MB)
                        if (file.size > 2 * 1024 * 1024) {
                            alert('Dosya boyutu 2MB\'dan büyük olamaz!');
                            this.value = '';
                            imagePreview.style.display = 'none';
                            return;
                        }

                        // Görsel önizleme
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewImage.src = e.target.result;
                            imagePreview.style.display = 'block';
                        };
                        reader.readAsDataURL(file);
                    } else {
                        imagePreview.style.display = 'none';
                    }
                });
            }

            // Reset butonu işlevi
            const resetBtn = document.getElementById('resetBtn');
            if (resetBtn) {
                resetBtn.addEventListener('click', function() {
                    imagePreview.style.display = 'none';
                    currentCountSpan.textContent = '0';
                });
            }

            // Form gönderilmeden önce doğrulama
            const createForm = document.getElementById('createPostForm');
            if (createForm) {
                createForm.addEventListener('submit', function(e) {
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

                    // Onay mesajı
                    if (!confirm('Yazıyı yayınlamak istediğinize emin misiniz?')) {
                        e.preventDefault();
                        return false;
                    }
                });
            }
        });
    </script>

@endsection
