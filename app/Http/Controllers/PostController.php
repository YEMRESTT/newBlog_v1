<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    // Tüm yazıları listele
    public function index()
    {
        $posts = Post::latest()->get();
        return view('posts.index', compact('posts'));
    }

    // Yeni yazı formu
    public function create()
    {
        return view('posts.create');
    }

    // Yazı kaydet
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        Post::create([
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('posts.index')->with('success', 'Yazı başarıyla eklendi!');
    }

    // Yazı düzenleme formu
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    // Yazı güncelleme
    public function update(Request $request, Post $post)
    {
        $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
        ]);

        $post->update($request->only(['title', 'content']));

        return redirect()->route('posts.index')->with('success', 'Yazı güncellendi!');
    }

    // Yazıyı sil
    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Yazı silindi!');
    }
}
