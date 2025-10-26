<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controller;

class AdminPermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['role:super-admin']); // sadece süper admin erişir
    }

    public function index()
    {
        $users = User::with('roles')->get();
        $permissions = Permission::all();
        return view('admin.permissions', compact('users', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Kullanıcının mevcut izinlerini sıfırla ve seçilenleri ata
        $user->syncPermissions($request->permissions ?? []);

        return redirect()->back()->with('success', 'Kullanıcı izinleri güncellendi.');
    }
}
