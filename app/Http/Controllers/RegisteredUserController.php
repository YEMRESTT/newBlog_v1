<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
class RegisteredUserController extends Controller
{

    public function store(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Otomatik olarak 'kullanici' rolü ver
        $user->assignRole('kullanici');

        Auth::login($user);
        return redirect()->route('home');
    }
}
