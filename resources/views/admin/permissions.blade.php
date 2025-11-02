@can('rol düzenle')
@extends('posts.layoutposts')

{{-- Sayfa başlığı (tarayıcı sekmesi) --}}
@section('title', 'İzin Yönetimi - Admin Panel')

{{-- Ana sayfa başlığı --}}
@section('main-title', 'Kullanıcı İzin Yönetimi')

{{-- Sayfa alt başlığı --}}
@section('subtitle', 'Kullanıcıların rol ve izinlerini detaylı şekilde yönetin')

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

    {{-- Hata mesajı bildirimi --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Bilgilendirme kartı --}}
    <div class="alert alert-info d-flex align-items-center mb-4" role="alert">
        <i class="bi bi-info-circle-fill fs-4 me-3"></i>
        <div>
            <strong>Bilgilendirme:</strong>
            Her kullanıcı için izinleri işaretleyip kaydet butonuna tıklayarak güncelleyebilirsiniz.
            Super Admin rolündeki kullanıcılar bu listede görünmez.
        </div>
    </div>

    {{-- Üst işlem çubuğu --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        {{-- Toplam kullanıcı ve izin sayısı --}}
        <div>
            <span class="badge bg-primary fs-6 me-2">
                <i class="bi bi-people-fill me-1"></i>
                {{ $users->filter(fn($u) => !$u->hasRole('super-admin'))->count() }} Kullanıcı
            </span>
            <span class="badge bg-info fs-6">
                <i class="bi bi-shield-lock-fill me-1"></i>
                {{ $permissions->count() }} İzin Türü
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

    {{-- İzin yönetimi tablosu --}}
    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-dark">
            <tr>
                <th scope="col" width="200">
                    <i class="bi bi-person me-1"></i>
                    Kullanıcı
                </th>
                <th scope="col" width="150">
                    <i class="bi bi-award me-1"></i>
                    Rol
                </th>
                <th scope="col">
                    <i class="bi bi-shield-check me-1"></i>
                    İzinler
                </th>
                <th scope="col" width="120" class="text-center">
                    <i class="bi bi-save me-1"></i>
                    İşlem
                </th>
            </tr>
            </thead>
            <tbody>
            {{-- Kullanıcıları listeleme döngüsü --}}
            @forelse($users as $user)
                {{-- Super admin'leri hariç tut --}}
                @if(!$user->hasRole('super-admin'))
                    <tr>
                        <form action="{{ route('admin.permissions.update', $user->id) }}" method="POST">
                            @csrf

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

                            {{-- Kullanıcı rolü --}}
                            <td>
                                @if($user->getRoleNames()->first())
                                    <span class="badge bg-info text-dark fs-6">
                                            <i class="bi bi-star-fill me-1"></i>
                                            {{ $user->getRoleNames()->first() }}
                                        </span>
                                @else
                                    <span class="badge bg-secondary">
                                            <i class="bi bi-dash-circle me-1"></i>
                                            Rol yok
                                        </span>
                                @endif
                            </td>

                            {{-- İzinler listesi --}}
                            <td>
                                <div class="row justify-content-center" style="row-gap: 20px; column-gap: 15px;">
                                    @foreach($permissions as $permission)
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-check form-switch p-2 border rounded {{ $user->hasPermissionTo($permission->name) ? 'bg-light' : '' }}">
                                                <input
                                                    type="checkbox"
                                                    name="permissions[]"
                                                    value="{{ $permission->name }}"
                                                    class="form-check-input"
                                                    id="perm_{{ $user->id }}_{{ $permission->id }}"
                                                    {{ $user->hasPermissionTo($permission->name) ? 'checked' : '' }}
                                                    role="switch">
                                                <label class="form-check-label" for="perm_{{ $user->id }}_{{ $permission->id }}">
                                                    <i class="bi bi-key text-muted me-1"></i>
                                                    <strong>{{ $permission->name }}</strong>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </td>

                            {{-- Kaydet butonu --}}
                            <td class="text-center">
                                <button type="submit" class="btn btn-success btn-sm" title="İzinleri Kaydet">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Kaydet
                                </button>
                            </td>

                        </form>
                    </tr>
                @endif
            @empty
                {{-- Kullanıcı bulunamadığında gösterilecek mesaj --}}
                <tr>
                    <td colspan="4" class="text-center text-muted py-4">
                        <i class="bi bi-person-x fs-1 d-block mb-2"></i>
                        <p class="mb-0">Yönetilebilir kullanıcı bulunmuyor.</p>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- İzin açıklamaları kartı --}}
    <div class="card mt-4 border-info">
        <div class="card-header bg-info text-white">
            <i class="bi bi-info-circle-fill me-2"></i>
            <strong>İzin Türleri Hakkında</strong>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($permissions as $permission)
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-check-circle-fill text-success me-2 mt-1"></i>
                            <div>
                                <strong>{{ $permission->name }}</strong>
                                <p class="text-muted small mb-0">
                                    Bu izne sahip kullanıcılar ilgili işlemi gerçekleştirebilir.
                                </p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Sayfalama (pagination) - Eğer varsa --}}
    @if(method_exists($users, 'links'))
        <div class="d-flex justify-content-center mt-4">
            {{ $users->links() }}
        </div>
    @endif

@endsection
@endcan
