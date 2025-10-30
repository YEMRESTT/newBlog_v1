@extends('posts.layoutposts')

{{-- Sayfa başlığı (tarayıcı sekmesi) --}}
@section('title', 'Kullanıcı Rolü Düzenle - Admin Panel')

{{-- Ana sayfa başlığı --}}
@section('main-title', 'Kullanıcı Rol Yönetimi')

{{-- Sayfa alt başlığı --}}
@section('subtitle', 'Kullanıcıya yeni rol atayın veya mevcut rolü güncelleyin')

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

    {{-- Kullanıcı bilgileri kartı --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h5 class="mb-0">
                <i class="bi bi-person-badge me-2"></i>
                Kullanıcı Bilgileri
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="row align-items-center">
                {{-- Kullanıcı avatarı --}}
                <div class="col-auto">
                    <div class="avatar-initial rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                         style="width: 80px; height: 80px; font-weight: 700; font-size: 2rem;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                </div>

                {{-- Kullanıcı detayları --}}
                <div class="col">
                    <h4 class="mb-2">
                        <i class="bi bi-person-circle text-primary me-2"></i>
                        {{ $user->name }}
                    </h4>
                    <p class="text-muted mb-2">
                        <i class="bi bi-envelope me-2"></i>
                        {{ $user->email }}
                    </p>
                    <div>
                        <span class="badge bg-info text-dark fs-6">
                            <i class="bi bi-award me-1"></i>
                            Mevcut Rol:
                            <strong>{{ $user->getRoleNames()->first() ?? 'Rol atanmamış' }}</strong>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Rol düzenleme formu kartı --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-gradient text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h5 class="mb-0">
                <i class="bi bi-shield-lock me-2"></i>
                Rol Ataması
            </h5>
        </div>

        <div class="card-body p-4">
            {{-- Bilgilendirme kutusu --}}
            <div class="alert alert-warning d-flex align-items-start mb-4" role="alert">
                <i class="bi bi-exclamation-triangle-fill fs-5 me-3 mt-1"></i>
                <div>
                    <strong>Dikkat!</strong>
                    Kullanıcının rolünü değiştirdiğinizde, bu değişiklik anında geçerli olacaktır.
                    Rol değişikliği kullanıcının yetkilerini doğrudan etkiler.
                </div>
            </div>

            {{-- Rol seçim formu --}}
            <form method="POST" action="{{ route('admin.update-role', $user->id) }}" id="roleUpdateForm">
                @csrf

                {{-- Rol seçim alanı --}}
                <div class="mb-4">
                    <label for="role" class="form-label">
                        <i class="bi bi-award me-1"></i>
                        Yeni Rol Seçin <span class="text-danger">*</span>
                    </label>
                    <select name="role"
                            id="role"
                            class="form-select form-select-lg @error('role') is-invalid @enderror"
                            required>
                        <option value="" disabled>Bir rol seçin...</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}"
                                {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                <i class="bi bi-star-fill"></i> {{ ucfirst($role->name) }}
                            </option>
                        @endforeach
                    </select>
                    @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">
                        <i class="bi bi-info-circle me-1"></i>
                        Kullanıcıya atanacak rolü yukarıdan seçin
                    </div>
                </div>

                {{-- Seçilen rol önizlemesi --}}
                <div id="rolePreview" class="alert alert-info d-none mb-4">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-eye fs-4 me-3"></i>
                        <div>
                            <strong>Seçilen Rol:</strong>
                            <span id="selectedRoleName" class="ms-2 badge bg-primary fs-6"></span>
                        </div>
                    </div>
                </div>

                {{-- Form butonları --}}
                <div class="d-flex gap-2 justify-content-between align-items-center pt-3 border-top">
                    <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i>
                        Geri Dön
                    </a>

                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-save-fill me-2"></i>
                        Rolü Güncelle
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Rol açıklamaları kartı --}}
    <div class="card border-info mt-4">
        <div class="card-header bg-info bg-opacity-10 border-info">
            <h6 class="mb-0 text-info">
                <i class="bi bi-info-circle-fill me-2"></i>
                Rol Seviyeleri ve Açıklamaları
            </h6>
        </div>
        <div class="card-body">
            <div class="row g-3">
                @foreach($roles as $role)
                    <div class="col-md-6">
                        <div class="card border-0 bg-light h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-shield-fill-check text-primary fs-4 me-3"></i>
                                    <div>
                                        <h6 class="mb-2">
                                            <span class="badge bg-primary">{{ ucfirst($role->name) }}</span>
                                        </h6>
                                        <p class="text-muted small mb-0">
                                            @if($role->name === 'super-admin')
                                                Tüm sistem yetkilerine sahip en üst düzey yönetici rolü.
                                            @elseif($role->name === 'admin')
                                                Blog içeriklerini ve kullanıcıları yönetebilir.
                                            @elseif($role->name === 'kullanici')
                                                Temel kullanıcı yetkileri ile sınırlı erişim.
                                            @else
                                                Bu role sahip kullanıcılar belirli yetkilere sahiptir.
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- İstatistik kartı --}}
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card border-primary text-center">
                <div class="card-body">
                    <i class="bi bi-people fs-1 text-primary"></i>
                    <h5 class="card-title mt-2">Kullanıcı ID</h5>
                    <p class="card-text display-6 fw-bold text-primary">#{{ $user->id }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-success text-center">
                <div class="card-body">
                    <i class="bi bi-calendar-check fs-1 text-success"></i>
                    <h5 class="card-title mt-2">Kayıt Tarihi</h5>
                    <p class="card-text fw-bold text-success">{{ $user->created_at->format('d.m.Y') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-info text-center">
                <div class="card-body">
                    <i class="bi bi-award fs-1 text-info"></i>
                    <h5 class="card-title mt-2">Toplam Rol</h5>
                    <p class="card-text display-6 fw-bold text-info">{{ $roles->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Özel stil ve script tanımlamaları --}}
    <style>
        /* Select focus efekti */
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        /* Buton hover efekti */
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
        }

        /* Avatar hover efekti */
        .avatar-initial {
            transition: all 0.3s ease;
        }

        .avatar-initial:hover {
            transform: scale(1.05) rotate(5deg);
            box-shadow: 0 8px 16px rgba(102, 126, 234, 0.3);
        }

        /* Rol kartları hover efekti */
        .card.bg-light {
            transition: all 0.3s ease;
        }

        .card.bg-light:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Select dropdown animasyonu */
        .form-select {
            transition: all 0.3s ease;
        }

        .form-select:hover {
            border-color: #667eea;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('role');
            const rolePreview = document.getElementById('rolePreview');
            const selectedRoleName = document.getElementById('selectedRoleName');
            const roleForm = document.getElementById('roleUpdateForm');

            // Rol seçimi değiştiğinde önizleme göster
            if (roleSelect) {
                roleSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    if (selectedOption.value) {
                        selectedRoleName.textContent = selectedOption.text.trim();
                        rolePreview.classList.remove('d-none');
                    } else {
                        rolePreview.classList.add('d-none');
                    }
                });

                // Sayfa yüklendiğinde mevcut rolü göster
                if (roleSelect.value) {
                    const selectedOption = roleSelect.options[roleSelect.selectedIndex];
                    selectedRoleName.textContent = selectedOption.text.trim();
                    rolePreview.classList.remove('d-none');
                }
            }

            // Form gönderilmeden önce onay
            if (roleForm) {
                roleForm.addEventListener('submit', function(e) {
                    const selectedRole = roleSelect.options[roleSelect.selectedIndex].text.trim();
                    const userName = '{{ $user->name }}';

                    if (!confirm(`"${userName}" kullanıcısına "${selectedRole}" rolünü atamak istediğinize emin misiniz?`)) {
                        e.preventDefault();
                        return false;
                    }
                });
            }
        });
    </script>

@endsection
