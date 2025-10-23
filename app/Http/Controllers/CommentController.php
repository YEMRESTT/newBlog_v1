<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class CommentController extends Controller
{
    public function createComment($post_id)
    {
        $roles= Role::all();
        return view('home.comment', compact('post_id', 'roles'));
    }

    public function addComment(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'comment' => 'required|string',
        ]);

        $comment = new Comment();
        $comment->post_id = $request->post_id;
        $comment->name = $request->name;
        $comment->email = $request->email;
        $comment->comment = $request->comment;
        $comment->save();

        return redirect('/')->with('success', 'Yorum başarıyla eklendi');
    }
}
