@extends('posts.layoutposts')

@section('title', 'Yazıyı Düzenle')
@section('main-title', 'Yazıyı Düzenle')

@section('content')
    <form method="POST" action="{{ route('posts.update', $post) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Başlık</label>
            <input type="text" name="title" id="title" class="form-control" value="{{ $post->title }}" required>
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">İçerik</label>
            <textarea name="content" id="content" rows="5" class="form-control" required>{{ $post->content }}</textarea>
        </div>


        <button type="submit" class="btn btn-primary">Güncelle</button>
        <a href="{{ route('posts.index') }}" class="btn btn-secondary">Geri Dön</a>
    </form>
@endsection
