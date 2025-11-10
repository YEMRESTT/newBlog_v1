<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Revolution\Google\Sheets\Facades\Sheets;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PostExportController extends Controller
{

    /**
     * Tüm postları çekip Google Sheets'e aktarır.
     */
    public function export(Request $request)
    {
        // 1) Tüm postları ve ilişkili kullanıcıyı (yazarı) çek
        $posts = Post::with('user')->latest()->get();

        if ($posts->isEmpty()) {
            return back()->with('error', 'Gönderilecek post bulunamadı.');
        }

        // 2) Başlık satırı
        $rows = [
            ['ID', 'Başlık', 'Yazar', 'Tarih', 'Resim URL']
        ];

        // 3) Her post için bir satır oluştur
        foreach ($posts as $post) {
            $author = $post->user ? $post->user->name : 'Bilinmiyor';
            $date = $post->created_at ? $post->created_at->format('d.m.Y') : '';
            // asset() helper'ı ile tam URL oluşturulur
            $imageUrl = $post->image ? asset('storage/' . $post->image) : '';

            $rows[] = [
                $post->id,
                $post->title,
                $author,
                $date,
                $imageUrl
            ];
        }

        // 4) Spreadsheet ID'yi .env dosyasındaki doğru değişkenden al
        $spreadsheetId = env('SHEETS_SPREADSHEET_ID');

        // Spreadsheet ID'nin tanımlı olup olmadığını kontrol et
        if (empty($spreadsheetId)) {
            // Hata mesajını döndür, böylece ID'nin eksik olduğunu görebilirsiniz.
            return back()->with('error', 'Google Sheet ID (.env dosyasında SHEETS_SPREADSHEET_ID) ayarlanmamış.');
        }

        // 5) Yeni bir sheet (tab) adı oluştur
        $sheetName = 'Posts Export ' . now()->format('Y-m-d_His');

        try {
            // Yeni bir sheet ekle ve verileri yaz
            Sheets::spreadsheet($spreadsheetId)->addSheet($sheetName);

            Sheets::spreadsheet($spreadsheetId)
                ->sheet($sheetName)
                ->append($rows);

            // 6) Sheet URL'sini oluştur
            $sheetUrl = "https://docs.google.com/spreadsheets/d/{$spreadsheetId}/edit#gid=0";

            return back()->with('success', 'Post Export tamamlandı! Google Sheet: ' . $sheetUrl);

        } catch (\Exception $e) {
            // API veya bağlantı hatası durumunda kullanıcıya bilgi ver
            return back()->with('error', 'Google Sheets API hatası: ' . $e->getMessage());
        }
    }
    /**
     * Google Sheets'ten veri çekip posts tablosuna ekler.
     */
    public function import(Request $request)
    {
        $spreadsheetId = env('SHEETS_SPREADSHEET_ID');
        // NOT: Verileri çekeceğiniz sekmenin adını buraya yazın
        $sheetName = 'Ekle';

        if (empty($spreadsheetId)) {
            return back()->with('error', 'Google Sheet ID (.env dosyasında SHEETS_SPREADSHEET_ID) ayarlanmamış.');
        }

        try {
            // 1) Sheet dosyasından tüm verileri çek
            $data = Sheets::spreadsheet($spreadsheetId)
                ->sheet($sheetName)
                ->get();

            // Veri gelmezse (Başlık satırı dahil)
            if ($data->isEmpty() || $data->count() < 1) {
                return back()->with('error', 'Belirtilen sheet dosyasında veri bulunamadı.');
            }

            // İlk satırı (başlıkları) atla
            $data->pull(0);
            $importedCount = 0;

            // Postları atamak için veritabanındaki ilk kullanıcıyı bul (Zorunlu ilişkiyi sağlamak için)
            $defaultUser = Auth::user();
            if (!$defaultUser) {
                return back()->with('error', 'İçe aktarma işlemi için atanacak bir kullanıcı bulunamadı (users tablosu boş).');
            }

            // 2) Gelen veriyi döngüye al ve Post modeline kaydet
            foreach ($data as $row) {
                // Sheet dosyanızdaki sütun sırasına dikkat edin!
                // Örnek: [A: Başlık, B: İçerik, C: Resim Yolu, D: Okunma Sayısı]
                $postData = [
                    'title' => $row[0] ?? null,
                    'content' => $row[1] ?? null,
                    'image' => $row[2] ?? null,
                    'read_count' => $row[3] ?? 0,
                    'user_id' => $defaultUser->id,
                ];

                // Gerekli alanların dolu olup olmadığını kontrol et
                if (empty($postData['title']) || empty($postData['content'])) {
                    continue;
                }

                // Yeni postu oluştur
                Post::create($postData);
                $importedCount++;
            }

            return back()->with('success', $importedCount . ' adet post, Google Sheets dosyasından başarıyla içeri aktarıldı.');

        } catch (Exception $e) {
            return back()->with('error', 'Google Sheets API hatası: ' . $e->getMessage());
        }
    }
}
