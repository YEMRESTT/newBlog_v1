@extends('posts.layoutposts')

{{-- Sayfa başlığı (tarayıcı sekmesi) --}}
@section('title', 'Yorum Ekle - Blog')

{{-- Ana sayfa başlığı --}}
@section('main-title', 'Yorum Ekle')

{{-- Sayfa alt başlığı --}}
@section('subtitle', 'Yazı hakkında düşüncelerinizi paylaşın')

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

    @can('add comment')
        {{-- Yorum formu kartı --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <h5 class="mb-0">
                    <i class="bi bi-chat-left-text me-2"></i>
                    Yorum Formu
                </h5>
            </div>

            <div class="card-body p-4">
                {{-- Bilgilendirme kutusu --}}
                <div class="alert alert-info d-flex align-items-start mb-4" role="alert">
                    <i class="bi bi-info-circle-fill fs-5 me-3 mt-1"></i>
                    <div>
                        <strong>Not:</strong>
                        Yorumunuz moderasyon sonrası yayınlanacaktır. Lütfen saygılı ve yapıcı yorumlar yazınız.
                    </div>
                </div>

                {{-- Yorum ekleme formu --}}
                <form action="{{ route('comments.add') }}" method="POST">
                    @csrf

                    {{-- Post ID (gizli alan) --}}
                    <input type="hidden" name="post_id" value="{{ $post_id }}">

                    {{-- Kullanıcı bilgileri bölümü --}}
                    <div class="row g-3 mb-4">
                        {{-- İsim alanı --}}
                        <div class="col-md-6">
                            <label for="name" class="form-label">
                                <i class="bi bi-person me-1"></i>
                                İsim Soyisim <span class="text-danger">*</span>
                            </label>
                            @auth
                                <input type="text"
                                       name="name"
                                       id="name"
                                       class="form-control form-control-lg"
                                       value="{{ Auth::user()->name }}"
                                       readonly>
                                <small class="text-muted">
                                    <i class="bi bi-lock-fill me-1"></i>
                                    Giriş yapmış kullanıcı olarak otomatik dolduruldu
                                </small>
                            @else
                                <input type="text"
                                       name="name"
                                       id="name"
                                       class="form-control form-control-lg @error('name') is-invalid @enderror"
                                       placeholder="Adınız ve soyadınız"
                                       value="{{ old('name') }}"
                                       required>
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            @endauth
                        </div>

                        {{-- E-posta alanı --}}
                        <div class="col-md-6">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope me-1"></i>
                                E-posta Adresi <span class="text-danger">*</span>
                            </label>
                            @auth
                                <input type="email"
                                       name="email"
                                       id="email"
                                       class="form-control form-control-lg"
                                       value="{{ Auth::user()->email }}"
                                       readonly>
                                <small class="text-muted">
                                    <i class="bi bi-lock-fill me-1"></i>
                                    Giriş yapmış kullanıcı olarak otomatik dolduruldu
                                </small>
                            @else
                                <input type="email"
                                       name="email"
                                       id="email"
                                       class="form-control form-control-lg @error('email') is-invalid @enderror"
                                       placeholder="ornek@email.com"
                                       value="{{ old('email') }}"
                                       required>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">
                                    <i class="bi bi-shield-check me-1"></i>
                                    E-posta adresiniz gizli kalacaktır
                                </small>
                            @endauth
                        </div>
                    </div>

                    {{-- Yorum içeriği alanı --}}
                    <div class="mb-4">
                        <label for="comment" class="form-label">
                            <i class="bi bi-chat-square-text me-1"></i>
                            Yorumunuz <span class="text-danger">*</span>
                        </label>
                        <textarea name="comment"
                                  id="comment"
                                  class="form-control form-control-lg @error('comment') is-invalid @enderror"
                                  rows="6"
                                  placeholder="Düşüncelerinizi buraya yazın..."
                                  required>{{ old('comment') }}</textarea>
                        @error('comment')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <i class="bi bi-pencil me-1"></i>
                            Minimum 10 karakter gereklidir
                        </div>
                    </div>

                    {{-- Form butonları --}}
                    <div class="d-flex gap-2 justify-content-between align-items-center">
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>
                            Geri Dön
                        </a>

                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-send-fill me-2"></i>
                            Yorumu Gönder
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Yorum kuralları kartı --}}
        <div class="card border-warning mt-4">
            <div class="card-header bg-warning bg-opacity-10 border-warning">
                <h6 class="mb-0 text-warning">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Yorum Kuralları
                </h6>
            </div>
            <div class="card-body">
                <ul class="mb-0">
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Saygılı ve yapıcı yorumlar yazınız
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Kişisel saldırı ve hakaret içeren yorumlar onaylanmayacaktır
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Reklam ve spam içerikler yasaktır
                    </li>
                    <li class="mb-0">
                        <i class="bi bi-check-circle text-success me-2"></i>
                        Yorumunuz 24 saat içinde moderasyon sürecinden geçecektir
                    </li>
                </ul>
            </div>
        </div>

    @else
        {{-- Yetki yoksa uyarı mesajı --}}
        <div class="card border-danger">
            <div class="card-body text-center py-5">
                <i class="bi bi-lock-fill fs-1 text-danger d-block mb-3"></i>
                <h4 class="text-danger">Yorum Yapma Yetkiniz Yok</h4>
                <p class="text-muted mb-4">Yorum yapabilmek için giriş yapmalısınız.</p>
                <a href="{{ route('login.form') }}" class="btn btn-primary">
                    <i class="bi bi-box-arrow-in-right me-2"></i>
                    Giriş Yap
                </a>
            </div>
        </div>
    @endcan

    {{-- Özel stil tanımlamaları --}}
    <style>
        /* Form input focus efekti */
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        /* Readonly input stili */
        .form-control[readonly] {
            background-color: #e9ecef;
            cursor: not-allowed;
        }

        /* Gönder butonu hover efekti */
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }

        /* Kart hover animasyonu */
        .card {
            transition: all 0.3s ease;
        }

        /* Textarea resize kontrolü */
        textarea.form-control {
            resize: vertical;
            min-height: 120px;
        }
    </style>

@endsection
