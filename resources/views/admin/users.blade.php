@extends('posts.layoutposts')

{{-- Sayfa başlığı (tarayıcı sekmesi) --}}
@section('title', 'Kullanıcı Yönetimi - Admin Panel')

{{-- Ana sayfa başlığı --}}
@section('main-title', 'Kullanıcı Listesi')

{{-- Sayfa alt başlığı --}}
@section('subtitle', 'Tüm kullanıcıları görüntüleyin ve rol atamalarını yönetin')

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

    {{-- Üst işlem çubuğu --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        {{-- Toplam kullanıcı sayısı --}}
        <div>
            <span class="badge bg-primary fs-6">
                <i class="bi bi-people-fill me-2"></i>
                Toplam {{ $users->count() }} Kullanıcı
            </span>
        </div>

        {{-- Çıkış yap butonu --}}
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger rounded-pill">
                <i class="bi bi-box-arrow-right me-2"></i>
                Çıkış Yap
            </button>
        </form>
    </div>

    {{-- Kullanıcılar tablosu --}}
    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-dark">
            <tr>
                <th scope="col" width="80">
                    <i class="bi bi-hash me-1"></i>
                    ID
                </th>
                <th scope="col">
                    <i class="bi bi-person me-1"></i>
                    Ad Soyad
                </th>
                <th scope="col">
                    <i class="bi bi-envelope me-1"></i>
                    E-posta
                </th>
                <th scope="col">
                    <i class="bi bi-shield-check me-1"></i>
                    Mevcut Rol
                </th>
                <th scope="col" width="180" class="text-center">
                    <i class="bi bi-gear me-1"></i>
                    İşlemler
                </th>
            </tr>
            </thead>
            <tbody>
            {{-- Kullanıcıları listeleme döngüsü --}}
            @forelse($users as $user)
                <tr>
                    {{-- Kullanıcı ID --}}
                    <td>
                        <strong class="text-primary">#{{ $user->id }}</strong>
                    </td>

                    {{-- Kullanıcı adı --}}
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar-initial rounded-circle bg-primary text-white me-2 d-flex align-items-center justify-content-center"
                                 style="width: 35px; height: 35px; font-weight: 600;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <strong>{{ $user->name }}</strong>
                        </div>
                    </td>

                    {{-- E-posta adresi --}}
                    <td>
                        <i class="bi bi-envelope text-muted me-1"></i>
                        <span class="text-muted">{{ $user->email }}</span>
                    </td>

                    {{-- Kullanıcı rolleri --}}
                    <td>
                        @if($user->roles->count() > 0)
                            @foreach($user->roles as $role)
                                <span class="badge bg-info text-dark me-1">
                                        <i class="bi bi-award me-1"></i>
                                        {{ $role->name }}
                                    </span>
                            @endforeach
                        @else
                            <span class="badge bg-secondary">
                                    <i class="bi bi-dash-circle me-1"></i>
                                    Rol atanmamış
                                </span>
                        @endif
                    </td>

                    {{-- İşlem butonları --}}
                    <td class="text-center">
                        <a href="{{ route('admin.edit-role', $user->id) }}"
                           class="btn btn-primary btn-sm"
                           title="Rolü Düzenle">
                            <i class="bi bi-pencil-square me-1"></i>
                            Rolü Düzenle
                        </a>
                    </td>
                </tr>
            @empty
                {{-- Kullanıcı bulunamadığında gösterilecek mesaj --}}
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        <i class="bi bi-person-x fs-1 d-block mb-2"></i>
                        <p class="mb-0">Henüz kayıtlı kullanıcı bulunmuyor.</p>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Sayfalama (pagination) - Eğer varsa --}}
    @if(method_exists($users, 'links'))
        <div class="d-flex justify-content-center mt-4">
            {{ $users->links() }}
        </div>
    @endif

    {{-- Kullanıcı istatistikleri kartı --}}
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <i class="bi bi-people fs-1 text-primary"></i>
                    <h5 class="card-title mt-2">Toplam Kullanıcı</h5>
                    <p class="card-text display-6 fw-bold text-primary">{{ $users->count() }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-info">
                <div class="card-body text-center">
                    <i class="bi bi-award fs-1 text-info"></i>
                    <h5 class="card-title mt-2">Rollü Kullanıcı</h5>
                    <p class="card-text display-6 fw-bold text-info">
                        {{ $users->filter(fn($user) => $user->roles->count() > 0)->count() }}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <i class="bi bi-dash-circle fs-1 text-warning"></i>
                    <h5 class="card-title mt-2">Rolsüz Kullanıcı</h5>
                    <p class="card-text display-6 fw-bold text-warning">
                        {{ $users->filter(fn($user) => $user->roles->count() === 0)->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>

@endsection
