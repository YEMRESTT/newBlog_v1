<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $post->title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; margin: 30px; }
        h1 { color: #333; }
        .meta { font-size: 13px; color: #555; margin-bottom: 15px; }
        img { max-width: 100%; margin: 10px 0; border-radius: 8px; }
        .content { font-size: 14px; line-height: 1.6; color: #222; }
    </style>
</head>
<body>
<h1>{{ $post->title }}</h1>
<p class="meta">
    Yazar: {{ $post->user ? $post->user->name : 'Bilinmiyor' }} |
    Tarih: {{ $post->created_at->format('d.m.Y') }}
</p>

@if($post->image)
    <img src="{{ public_path('storage/'.$post->image) }}" alt="Gönderi Görseli">
@endif

<div class="content">{!! $post->content !!}</div>
</body>
</html>
