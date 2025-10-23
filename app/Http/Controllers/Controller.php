<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class Controller
{
    public function index()
    {
        // Tüm kullanıcıları veritabanından al
        $users = User::with('roles')->get();

        // 'users.index' adlı görünüme gönder
        return view('posts.users-list', compact('users'));
    }
}
