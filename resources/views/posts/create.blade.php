@extends('posts.layoutposts')

@section('title', 'Yeni Yazı Ekle')
@section('main-title', 'Yeni Yazı Ekle')

@section('content')
    <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">Başlık</label>
            <input type="text" name="title" id="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">İçerik</label>
            <textarea name="content" id="content" rows="5" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label"> Görsel</label>
            <input type="file" name="image" id="image" class="form-control">


        </div>
        <button type="submit" class="btn btn-success">Kaydet</button>
        <a href="{{ route('posts.index') }}" class="btn btn-secondary">Geri Dön</a>
    </form>
@endsection
