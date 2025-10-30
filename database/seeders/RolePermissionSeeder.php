<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // ---- İzinleri oluştur ----

        //kullanıcı
        Permission::firstOrCreate(['name' => 'view post']); // post görüntüle
        Permission::firstOrCreate(['name' => 'add comment']); // yorum yapmak
        Permission::firstOrCreate(['name' => 'see comments']); // yorumları gör
        //admin
        Permission::firstOrCreate(['name' => 'create post']); // post oluştur
        Permission::firstOrCreate(['name' => 'edit post']);  // post düzenle
        Permission::firstOrCreate(['name' => 'delete post']); // post sil
        Permission::firstOrCreate(['name' => 'kullanıcı yönet']); // post sil



        // ---- Roller ----
        $kullanici = Role::firstOrCreate(['name' => 'kullanici']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);

        // ---- Rollerle izinleri ilişkilendir ----
        $kullanici->givePermissionTo(['view post','add comment','see comments']); // Sadece yorum yapabilir
        $admin->givePermissionTo(['view post','add comment','see comments',
            'create post','edit post','delete post']);
        $superAdmin->givePermissionTo(Permission::all()); // Tüm izinler
    }
}
