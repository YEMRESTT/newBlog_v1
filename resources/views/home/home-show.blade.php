@extends('layout.accound')

@section('title', $post->title)
@section('main-title', $post->title)
@section('subtitle', 'Yazan: ' . $post->user->name)

@section('contend')
    <div class="container">
        <p>{{ $post->content }}</p>
        <hr>
        <p>Okunma sayısı: {{ $post->read_count }}</p>

    @if($post->image)
            <img src="{{ asset('storage/' . $post->image) }}"
                 alt="Kapak Görseli"
                 width="200"
                 class="rounded mb-2"> <br>
        @endif

        <a href="{{ route('home') }}">← Geri dön</a>



    </div>
@endsection
