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

        //Admin paneli kısmı
        Permission::firstOrCreate(['name' => 'admin paneli görüntüle']);
        Permission::firstOrCreate(['name' => 'yeni yazı ekle']);
        Permission::firstOrCreate(['name' => 'resim ekle']); // yeni yazı ekleyemezsem bunuda ekleyemeyecem
        Permission::firstOrCreate(['name' => 'başlık gör']);
        Permission::firstOrCreate(['name' => 'yazan gör']);
        Permission::firstOrCreate(['name' => 'tarih gör']);
        Permission::firstOrCreate(['name' => 'okunma sayısı gör']);
        Permission::firstOrCreate(['name' => 'düzenle']);
        Permission::firstOrCreate(['name' => 'sil']);

        // Paylaşımlar paneli kısmı
        Permission::firstOrCreate(['name' => 'paylaşımlar paneli görüntüle']);
        Permission::firstOrCreate(['name' => 'devamını oku']);
        Permission::firstOrCreate(['name' => 'yorum ekle']);


        // kullanıcı listesi paneli
        Permission::firstOrCreate(['name' => 'kullanıcı listesi paneli görüntüle']);
        Permission::firstOrCreate(['name' => 'ıd gör']);
        Permission::firstOrCreate(['name' => 'ad soyad gör']);
        Permission::firstOrCreate(['name' => 'eposta gör']);
        Permission::firstOrCreate(['name' => 'mevcut rol']);
        Permission::firstOrCreate(['name' => 'rol düzenle']);



        // ---- Roller ----
        $kullanici = Role::firstOrCreate(['name' => 'kullanici']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);

        // ---- Rollerle izinleri ilişkilendir ----
        $kullanici->givePermissionTo(['paylaşımlar paneli görüntüle','devamını oku','yorum ekle']); // Sadece yorum yapabilir
        $admin->givePermissionTo(['admin paneli görüntüle','yeni yazı ekle','resim ekle','başlık gör','yazan gör', 'tarih gör','okunma sayısı gör',
            'düzenle','sil','paylaşımlar paneli görüntüle','devamını oku','yorum ekle']);
        $superAdmin->givePermissionTo(Permission::all()); // Tüm izinler
        $basicrole = Role::create(['name' => 'basic-role']);

    }
}
