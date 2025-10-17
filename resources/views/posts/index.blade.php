@extends('posts.layoutposts')

@section('title', 'Yazılar')
@section('main-title', 'Tüm Blog Yazıları')
@section('subtitle', 'Aşşağıda')



@section('content')
    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('posts.create') }}" class="btn btn-primary mb-3">Yeni Yazı Ekle</a>

        <table class="table table-bordered">
            <thead class="table-dark">
            <tr>
                <th>Başlık</th>
                <th>Yazan</th>
                <th>Tarih</th>
                <th>Okunma sayısı</th>
                <th width="180">İşlemler</th>

            </tr>
            </thead>
            <tbody>
            @foreach ($posts as $post)
                <tr>
                    <td>{{ $post->title }}</td>
                    <td>{{ $post->user->name }}</td>
                    <td>{{ $post->created_at->format('d.m.Y H:i') }}</td>
                    <td>{{ $post->read_count }}</td>
                    <td>
                        <a href="{{ route('posts.edit', $post) }}" class="btn btn-warning btn-sm">Düzenle</a>
                        <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Sil</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
            <div class="container-fluid d-flex">


                {{-- ms-auto sağa yaslamak için çalışır ama sadece d-flex içinde --}}
                <div class="ms-auto">
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit"  class="btn rounded-pill btn-danger">Çıkış yap</button>

                        </button>
                    </form>
                </div>
            </div>

    </div>
@endsection
