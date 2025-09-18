<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{

    public function index()
    {
        $posts = Post::all();
        return view('posts.index', ['posts' => $posts]);
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
    // バリデーション
        $request->validate([
        'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048', // 2MBまで
    ]);

    // ストレージに保存（storage/app/public/photos に保存）
        $image_path = $request->file('photo')->store('photos', 'public');

    // DBに保存

        $post = new Post();
        $post->title = $request->title;
        $post->body = $request->body;
        $post->imag_path  = $image_path;

        $post->save();

        return redirect()->route('posts.index');

    }

}
