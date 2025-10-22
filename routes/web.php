<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\HomeController;
use App\http\controllers\CommentController;
use App\http\controllers\Controller;




// Giriş ve kayıt sayfaları
Route::get('/register', [AuthController::class, 'showRegister'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login.form');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin paneli (sadece giriş yapmış kullanıcı)
//Route::middleware('auth')->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [PostController::class, 'index'])->name('posts.index');
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
});

// Ana sayfa (yazı listesi)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Yazı detay sayfası
Route::get('/yazi/{id}', [HomeController::class, 'show'])->name('home.show');


//yorum sayfaları

Route::get('/comments/{post_id}', [CommentController::class, 'createComment'])->name('comments.create');
Route::post('/comments', [CommentController::class, 'addComment'])->name('comments.add');

// okuma sayısı

Route::post('/posts/{id}/increment-read', [PostController::class, 'incrementReadCount'])->name('posts.incrementRead');


//kullanıcı listesi sayfası
Route::get('/users', [Controller::class, 'index'])->name('posts.users-list');
