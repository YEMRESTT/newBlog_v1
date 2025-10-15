<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Comment;

class HomeController extends Controller
{
    // Ana sayfa: tüm yazıları listeler
    public function index()
    {
        $posts = Post::with('user','comments')->latest()->get();
        return view('home.home', compact('posts'));

    }

    // Tek bir yazıyı göster
    public function show($id)
    {
        $post = Post::with('user', 'comments')->findOrFail($id);
        return view('home.home-show', compact('post'));
    }
}
