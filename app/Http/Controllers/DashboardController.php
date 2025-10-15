<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // Admin sayfası
    public function index()
    {
        $user = Auth::user();
        return view('dashboard', compact('user'));
    }
}
