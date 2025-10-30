@extends('posts.layoutposts')

{{-- Sayfa başlığı (tarayıcı sekmesi) --}}
@section('title', 'Blog Yazıları - Yönetim Paneli')

{{-- Ana sayfa başlığı --}}
@section('main-title', 'Tüm Blog Yazıları')

{{-- Sayfa alt başlığı --}}
@section('subtitle', 'Sistemdeki tüm blog yazılarını görüntüleyin ve yönetin')

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
        {{-- Yeni yazı ekleme butonu --}}
        @can('create post')
            <a href="{{ route('posts.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>
                Yeni Yazı Ekle
            </a>
        @else
            <div></div>
        @endcan

        {{-- Çıkış yap butonu --}}
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger rounded-pill">
                <i class="bi bi-box-arrow-right me-2"></i>
                Çıkış Yap
            </button>
        </form>
    </div>

    {{-- Blog yazıları tablosu --}}
    <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle text-center">
            <thead class="table-dark">
            <tr>
                <th scope="col" class="text-start">
                    <i class="bi bi-file-text me-1"></i>
                    Başlık
                </th>
                <th scope="col">
                    <i class="bi bi-person me-1"></i>
                    Yazan
                </th>
                <th scope="col">
                    <i class="bi bi-calendar-event me-1"></i>
                    Tarih
                </th>
                <th scope="col">
                    <i class="bi bi-eye me-1"></i>
                    Okunma
                </th>
                <th scope="col" width="200" >
                    <i class="bi bi-gear me-1"></i>
                    İşlemler
                </th>
            </tr>
            </thead>
            <tbody class="table-group-divider">
            {{-- Yazıları listeleme döngüsü --}}
            @forelse ($posts as $post)
                <tr>
                    {{-- Yazı başlığı --}}
                    <td class="text-start fw-semibold">
                        <strong>{{ $post->title }}</strong>
                    </td>

                    {{-- Yazar adı --}}
                    <td>
                            <span class="badge bg-info text-dark px-3 py-2">
                                {{ $post->user->name }}
                            </span>
                    </td>

                    {{-- Yayınlanma tarihi --}}
                    <td>
                        <small class="text-muted">
                            {{ $post->created_at->format('d.m.Y H:i') }}
                        </small>
                    </td>

                    {{-- Okunma sayısı --}}
                    <td>
                            <span class="badge bg-secondary px-3 py-2">
                                {{ $post->read_count }}
                            </span>
                    </td>

                    {{-- İşlem butonları --}}
                    <td >
                        <div class="d-inline-flex gap-2">
                            {{-- Düzenle butonu --}}
                            @can('edit post')
                                <a href="{{ route('posts.edit', $post) }}"
                                   class="btn btn-warning btn-sm"
                                   title="Düzenle">
                                    <i class="bi bi-pencil-square"></i>
                                    Düzenle
                                </a>
                            @endcan

                            {{-- Sil butonu --}}
                            @can('delete post')
                                <form action="{{ route('posts.destroy', $post) }}"
                                      method="POST"
                                      onsubmit="return confirm('Bu yazıyı silmek istediğinize emin misiniz?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-danger btn-sm"
                                            title="Sil">
                                        <i class="bi bi-trash"></i>
                                        Sil
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                {{-- Yazı bulunamadığında gösterilecek mesaj --}}
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        <p class="mb-0">Henüz hiç yazı eklenmemiş.</p>
                        @can('create post')
                            <a href="{{ route('posts.create') }}" class="btn btn-primary btn-sm mt-2">
                                <i class="bi bi-plus-circle me-1"></i>
                                İlk Yazıyı Ekle
                            </a>
                        @endcan
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Sayfalama (pagination) - Eğer varsa --}}
    @if(method_exists($posts, 'links'))
        <div class="d-flex justify-content-center mt-4">
            {{ $posts->links() }}
        </div>
    @endif

@endsection
