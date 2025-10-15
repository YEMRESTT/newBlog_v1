@extends('layout.accound')

@section('title', $post->title)
@section('main-title', $post->title)
@section('subtitle', 'Yazan: ' . $post->user->name)

@section('contend')
    <div class="container">
        <p>{{ $post->content }}</p>
        <hr>
        <a href="{{ route('home') }}">← Geri dön</a>
    </div>
@endsection
