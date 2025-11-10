<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Revolution\Google\Sheets\Facades\Sheets;
use Illuminate\Support\Facades\Storage;

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
}
