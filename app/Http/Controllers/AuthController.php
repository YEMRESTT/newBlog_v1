<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Laravel\Socialite\Facades\Socialite; // EKLENDI
use Exception; // EKLENDI

class AuthController extends Controller
{



    // kayıt , giriş, çıkış için kullanılıyor

    public function showRegister()
    {


        return view('auth.register');
    }

    public function register(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Seeder'da oluşturduğun "basic-role" rolünü bul
        $basicRole = Role::where('name', 'basic-role')->first();

        if ($basicRole) {
            // Kullanıcıya rolü ata
            $user->assignRole($basicRole);

            // Bu rolün varsayılan izinlerini kullanıcıya da ekle (dinamik olarak)
            $user->syncPermissions([
                'paylaşımlar paneli görüntüle',
                'devamını oku',
                'yorum ekle'
            ]);
        } else {
            // Rol bulunmazsa hata mesajı (sadece geliştirme aşamasında faydalı)
            return redirect()->back()->with('error', 'basic-role rolü bulunamadı. Lütfen seeder dosyanı çalıştır.');
        }


        Auth::login($user);
        return redirect()->route('home');


        // return redirect()->route('dashboard')->with('success', 'Kayıt başarılı, hoş geldin!');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'Girdiğin bilgiler hatalı.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }


    // ... mevcut metodlarınız (showRegister, register, showLogin, login, logout)

    /**
     * Google'a yönlendirme
     * Kullanıcıyı Google OAuth ekranına yönlendirir
     */
    public function redirectToGoogle()
    {
        try {
            return Socialite::driver('google')
                ->scopes(['openid', 'profile', 'email']) // İstenen bilgiler
                ->redirect();
        } catch (Exception $e) {
            return redirect()->route('login.form')
                ->with('error', 'Google ile bağlantı kurulamadı. Lütfen tekrar deneyin.');
        }
    }

    /**
     * Google'dan geri dönüş (Callback)
     * Google'dan dönen kullanıcı bilgilerini işler
     */
    public function handleGoogleCallback()
    {
        try {
            // Google'dan kullanıcı bilgilerini al
            $googleUser = Socialite::driver('google')->user();

            // Bu Google ID ile kayıtlı kullanıcı var mı kontrol et
            $user = User::where('google_id', $googleUser->getId())->first();

            if ($user) {
                // Kullanıcı zaten kayıtlı - sadece giriş yap
                Auth::login($user);
                return redirect()->route('home')
                    ->with('success', 'Hoş geldiniz, ' . $user->name . '!');
            }

            // Google ID ile kullanıcı yoksa, email ile kontrol et
            // (Önceden email/password ile kayıt olmuş olabilir)
            $existingUser = User::where('email', $googleUser->getEmail())->first();

            if ($existingUser) {
                // Mevcut hesaba Google ID'yi ekle
                $existingUser->update([
                    'google_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);

                Auth::login($existingUser);
                return redirect()->route('home')
                    ->with('success', 'Google hesabınız bağlandı! Hoş geldiniz.');
            }

            // Tamamen yeni kullanıcı - kayıt oluştur
            $newUser = $this->createGoogleUser($googleUser);

            // Kullanıcıya basic-role rolünü ata
            $this->assignBasicRole($newUser);

            // Otomatik giriş yap
            Auth::login($newUser);

            return redirect()->route('home')
                ->with('success', 'Hesabınız başarıyla oluşturuldu! Hoş geldiniz.');

        } catch (Exception $e) {
            // Hata loglama (production'da önemli)
            \Log::error('Google OAuth Error: ' . $e->getMessage());

            return redirect()->route('login.form')
                ->with('error', 'Google ile giriş sırasında bir hata oluştu. Lütfen tekrar deneyin.');
        }
    }

    /**
     * Google kullanıcısını veritabanında oluşturur
     *
     * @param \Laravel\Socialite\Contracts\User $googleUser
     * @return User
     */
    private function createGoogleUser($googleUser)
    {
        return User::create([
            'name' => $googleUser->getName(),
            'email' => $googleUser->getEmail(),
            'google_id' => $googleUser->getId(),
            'avatar' => $googleUser->getAvatar(),
            'password' => null, // Google kullanıcıları için şifre yok
            'email_verified_at' => now(), // Google zaten email doğrulaması yapmış
        ]);
    }

    /**
     * Yeni kullanıcıya basic-role rolünü ve izinleri atar
     * (Mevcut register metodunuzdakiyle aynı mantık)
     *
     * @param User $user
     * @return void
     */
    private function assignBasicRole(User $user)
    {
        $basicRole = Role::where('name', 'basic-role')->first();

        if ($basicRole) {
            // Kullanıcıya rolü ata
            $user->assignRole($basicRole);

            // Bu rolün varsayılan izinlerini kullanıcıya da ekle
            $user->syncPermissions([
                'paylaşımlar paneli görüntüle',
                'devamını oku',
                'yorum ekle'
            ]);
        } else {
            // Rol yoksa log at (production'da sessizce atlanabilir)
            \Log::warning('basic-role rolü bulunamadı. Kullanıcı: ' . $user->email);
        }
    }


}
