<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Barryvdh\DomPDF\Facade\Pdf;

class PostPDFController extends Controller
{
    public function generatePDF($id)
    {
        $post = Post::with('user')->findOrFail($id);

        $pdf = Pdf::loadView('home.post-pdf', compact('post'));
        return $pdf->download('post_'.$id.'.pdf');
    }

    public function preview($id)
    {
        $post = Post::findOrFail($id);

        // PDF görünümünü blade üzerinden render ediyoruz
        $pdf = Pdf::loadView('home.post-pdf', compact('post'));

        // PDF'yi inline olarak tarayıcıda göster
        return $pdf->stream($post->title . '.pdf');
    }

}
