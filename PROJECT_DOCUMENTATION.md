# 🧭 Proje Özeti

Bu proje, Laravel 12 üzerine inşa edilmiş basit ama genişletilebilir bir blog uygulamasıdır. Kullanıcıların kayıt/giriş yapması, roller ve izinler ile yetkilendirme, yazı (post) oluşturma/düzenleme/silme, yorum ekleme, okunma sayısı takibi, yazıların PDF çıktısının alınması ve Google Sheets ile veri dışa/içe aktarma özellikleri sunar.

---

# ⚙️ Temel Özellikler

- Kullanıcı kayıt, e-posta/şifre ile giriş ve çıkış
- Google OAuth (Socialite) ile giriş ve hesap bağlama
- Roller ve izinler (Spatie Laravel Permission)
- Yazı yönetimi: listeleme, oluşturma, düzenleme, silme
- Yorum ekleme ve görüntüleme
- Okunma sayısı (read_count) takibi
- Yazıların PDF olarak indirilmesi veya tarayıcıda görüntülenmesi (barryvdh/laravel-dompdf)
- Google Sheets’e postların aktarılması ve dış kaynaktan içe aktarım (revolution/laravel-google-sheets)
- Basit admin paneli: kullanıcılar ve roller, kullanıcı izinlerini yönetme

---

# 🏗️ Mimari ve Yapı

- Framework: Laravel 12
- PHP Sürümü: ^8.2
- Katmanlar:
  - Controllers: HTTP isteklerinin işlenmesi, yetkilendirme ve iş akışı
  - Models: Eloquent ORM ile veri modelleri ve ilişkiler
  - Migrations: Veritabanı şema yönetimi
  - Views: Blade ile sunum katmanı
  - Routes: Web rotaları (HTTP endpoint’leri)
  - Config: Üçüncü parti servis ve paket yapılandırmaları
  - Seeders: Başlangıç verileri ve rol/izin oluşturma

---

# 🧩 Temel Bileşenler

## Controllerlar

- AuthController
  - showRegister, register: Kullanıcı kaydı ve basic-role ataması, varsayılan izinlerin verilmesi
  - showLogin, login, logout: Kimlik doğrulama akışı
  - redirectToGoogle, handleGoogleCallback: Google OAuth ile giriş/hesap bağlama
- HomeController
  - index: Post + user + comments ilişkisi ile ana sayfa listesi
  - show: Tek post detay, read_count artışı
- PostController
  - index, create, store, edit, update, destroy: Post yönetimi (resim yükleme ve storage/public kullanımı)
- CommentController
  - createComment, addComment: Yorum formu ve doğrulama ile ekleme
- AdminController
  - index: Kullanıcı listeleme (roller ile)
  - edit, update: Kullanıcı rolünü düzenleme
- AdminPermissionController
  - index: Kullanıcıları ve tüm izinleri listeleme
  - update: Bir kullanıcının izinlerini sync ederek güncelleme
- PostPDFController
  - generatePDF, preview: Post’u PDF’e çevirme ve tarayıcıda gösterme
- PostExportController
  - export: Postları Google Sheets’e aktarım
  - import: Google Sheets’ten posts tablosuna içe aktarma (aktif kullanıcıya bağlayarak)

## Modeller

- User
  - Traits: HasFactory, Notifiable, HasRoles
  - Fillable: name, email, is_admin, created_at, password, google_id, avatar
- Post
  - Fillable: title, content, image, user_id, read_count
  - İlişkiler: belongsTo(User), hasMany(Comment)
- Comment
  - Fillable: id, post_id, name, email, comment
  - İlişki: belongsTo(Post)

## Routelar (routes/web.php)

- Kimlik Doğrulama
  - GET /register → AuthController@showRegister (register.form)
  - POST /register → AuthController@register (register)
  - GET /login → AuthController@showLogin (login.form)
  - POST /login → AuthController@login (login)
  - POST /logout → AuthController@logout (logout)
- Google OAuth
  - GET /auth/google → AuthController@redirectToGoogle (auth.google)
  - GET /auth/google/callback → AuthController@handleGoogleCallback (auth.google.callback)
- Admin / Post Yönetimi (Spatie permission middleware)
  - GET /dashboard → PostController@index (posts.index) [permission: admin paneli görüntüle]
  - GET /posts/create → PostController@create (posts.create) [permission: yeni yazı ekle]
  - POST /posts → PostController@store (posts.store) [permission: yeni yazı ekle]
  - GET /posts/{post}/edit → PostController@edit (posts.edit) [permission: düzenle]
  - PUT /posts/{post} → PostController@update (posts.update) [permission: düzenle]
  - DELETE /posts/{post} → PostController@destroy (posts.destroy) [permission: sil]
- Ana Sayfa / Detay
  - GET / → HomeController@index (home)
  - GET /yazi/{id} → HomeController@show (home.show) [permission: devamını oku]
- Yorumlar
  - GET /comments/{post_id} → CommentController@createComment (comments.create) [permission: yorum ekle]
  - POST /comments → CommentController@addComment (comments.add) [permission: yorum ekle]
- Okunma Sayısı
  - POST /posts/{id}/increment-read → PostController@incrementReadCount (posts.incrementRead) [Not: Controller’da metod tanımı görünmüyor]
- Super Admin
  - GET /admin/users → AdminController@index (admin.users) [permission: kullanıcı listesi paneli görüntüle]
  - GET /admin/users/{id}/edit → AdminController@edit (admin.edit-role) [permission: kullanıcı listesi paneli görüntüle]
  - POST /admin/users/{id} → AdminController@update (admin.update-role) [permission: kullanıcı listesi paneli görüntüle]
  - GET /admin/permissions → AdminPermissionController@index (admin.permissions) [permission: rol düzenle]
  - POST /admin/permissions/{id} → AdminPermissionController@update (admin.permissions.update) [permission: rol düzenle]
- PDF
  - GET /post/{id}/pdf → PostPDFController@generatePDF (posts.download)
  - GET /posts/{id}/pdf-preview → PostPDFController@preview (posts.preview)
- Google Sheets
  - POST /posts/export-to-sheets → PostExportController@export (posts.exportToSheets)
  - GET /import/posts → PostExportController@import (posts.import)

## Viewlar (resources/views)

- auth: login.blade.php, register.blade.php
- home: home.blade.php, home-show.blade.php, comment.blade.php, post-pdf.blade.php
- posts: index.blade.php, create.blade.php, edit.blade.php, layoutposts.blade.php
- admin: users.blade.php, edit-role.blade.php, permissions.blade.php
- layout: app.blade.php, accound.blade.php
- welcome.blade.php

## Middleware

- Spatie Permission ile gelen `permission:<izin-adı>` middleware’leri aktif olarak kullanılıyor.
- Bazı yönetim aksiyonları doğrudan permission ile korunuyor; ek olarak auth middleware’i ile giriş zorunluluğu sağlanması önerilir.

## Servisler ve Yapılandırmalar

- config/services.php: postmark, resend, ses, slack, google (client_id, client_secret, redirect)
- config/google.php: Google API client ayarları, Sheets ve Drive kapsamları, service account opsiyonları ve `spreadsheet_id` env değeri
- Not: PostExportController `SHEETS_SPREADSHEET_ID` değişkenini kullanıyor; config/google.php ise `GOOGLE_SHEET_ID` bekliyor. Ortam değişkenlerinde tutarlılık sağlanmalı.
- Not: services.php `GOOGLE_REDIRECT_URI` kullanırken google.php `GOOGLE_REDIRECT` bekliyor; tek bir anahtar üzerinde standardize edilmesi önerilir.

---

# 📦 Bağımlılıklar ve Paketler

- laravel/framework ^12.0
- barryvdh/laravel-dompdf ^3.1 (PDF üretimi)
- laravel/socialite ^5.23 (Google OAuth)
- revolution/laravel-google-sheets ^7.1 (Sheets entegrasyonu)
- spatie/laravel-permission ^6.21 (Rol ve izin yönetimi)
- laravel/tinker ^2.10.1 (CLI)

Geliştirme Bağımlılıkları:
- phpunit/phpunit ^11.5.3, mockery/mockery ^1.6 (test)
- nunomaduro/collision ^8.6 (CLI hata raporlama)
- laravel/pint ^1.24 (kod stil)
- laravel/sail ^1.41 (Docker dev ortamı)
- laravel/pail ^1.2.2 (Laravel log görüntüleyici)
- fakerphp/faker ^1.23 (test verisi)

Scripts (composer.json):
- setup: composer install, .env kopyalama, key:generate, migrate --force, npm install, npm run build
- dev: concurrently ile artisan serve, queue:listen, pail ve Vite dev server’ını birlikte çalıştırır
- test: artisan config:clear ve artisan test

---

# 🌐 API Uç Noktaları (Varsa)

Uygulama web tabanlı rotalar sunar; JSON API tasarımı bulunmamaktadır. Tüm uç noktalar HTTP GET/POST/PUT/DELETE olarak form tabanlı işlevlerle çalışır. İhtiyaç halinde API Resource Controller ve Sanctum/Passport ile genişletilebilir.

---

# 🔒 Güvenlik ve Konfigürasyonlar

- Kimlik doğrulama: Laravel Auth + Socialite
- Yetkilendirme: Spatie Permission ile rol/izin kontrolü (route middleware)
- Form doğrulama: Controller seviyesinde request validate çağrıları
- Şifreler: Varsayılan olarak hashed cast; Google kullanıcıları için şifre nullable
- CSRF: Laravel’in varsayılan koruması aktif
- Gizli anahtarlar: .env dosyasında tutulmalı, repoya eklenmemeli
- Dosya yükleme: storage/app/public altında; `php artisan storage:link` ile public erişim

---

# 🚀 Kurulum ve Yayına Alma

## Geliştirme Kurulumu

1. Depoyu klonlayın
2. `.env` dosyasını oluşturun (`cp .env.example .env`) ve aşağıları doldurun:
   - DB_CONNECTION, DB_HOST, DB_PORT, DB_DATABASE, DB_USERNAME, DB_PASSWORD
   - APP_URL
   - Google OAuth: GOOGLE_CLIENT_ID, GOOGLE_CLIENT_SECRET, GOOGLE_REDIRECT_URI (veya GOOGLE_REDIRECT)
   - Google Sheets: SHEETS_SPREADSHEET_ID (veya config/google.php ile uyumlu GOOGLE_SHEET_ID)
3. `composer install`
4. `php artisan key:generate`
5. `php artisan migrate`
6. Rolleri ve izinleri eklemek için (opsiyonel): `php artisan db:seed --class=RolePermissionSeeder`
7. Dosya yüklemeleri için: `php artisan storage:link`
8. Frontend derleme: `npm install` ve geliştirme için `npm run dev` veya prod için `npm run build`
9. Hızlı başlatma: `composer run dev` (concurrently ile servisler başlar)

## Yayına Alma (Production)

- Web sunucusunu `public/index.php`’ye yönlendirin
- ENV değişkenlerini production değerleriyle ayarlayın
- `composer install --no-dev` ve `php artisan migrate --force`
- `php artisan storage:link`
- Queue (database) ve log rotaları için gerekli servisleri yapılandırın (Supervisor)
- `npm run build` sonrası derlenmiş asset’leri sunun
- Önbellekleri optimize edin: `php artisan config:cache`, `php artisan route:cache`, `php artisan view:cache`

---



# 🗂️ Dosya Ağacı Özeti

- app/
  - Http/Controllers: AuthController, HomeController, PostController, CommentController, AdminController, AdminPermissionController, PostExportController, PostPDFController
  - Models: User, Post, Comment
  - Providers: AppServiceProvider
- config/: services.php, google.php, permission.php vb.
- database/
  - migrations: users, posts, comments, permission tabloları ve ek alanlar (image, read_count, google_id, avatar, is_admin)
  - seeders: DatabaseSeeder, RolePermissionSeeder
- resources/
  - views: auth, home, posts, admin, layout
- routes/: web.php, console.php
- public/: index.php ve assetler

---
ı yönetim rotaları sadece permission ile korunuyor; ek olarak auth middleware ile giriş zorunluluğu sağlanması tavsiye edilir.
