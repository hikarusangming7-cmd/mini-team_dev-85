<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

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
            'title' => 'required|string|max:30',
            'body' => 'required|string|max:140',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // ストレージに保存（storage/app/public/photos に保存）
        $image_path = null;
        if ($request->hasFile('photo')) {
            $image_path = $request->file('photo')->store('photos', 'public');
        }


        // DBに保存

        $post = new Post();
        $post->title = $request->title;
        $post->body = $request->body;
        $post->image_path  = $image_path;
        $post->user_id = Auth::id();

        $post->save();

        return redirect()->route('posts.index')->with('success', '投稿しました！');
    }

    public function edit($id)
    {
        $post = Post::find($id);

        return view('posts.edit', ['post' => $post]);
    }

    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        // バリデーション
        $request->validate([
            'title' => 'required|string|max:30',
            'body' => 'required|string|max:140',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // ストレージに保存（storage/app/public/photos に保存）
        $image_path = $post->image_path;
        if ($request->hasFile('photo')) {
            $image_path = $request->file('photo')->store('photos', 'public');
        }

        $post->title = $request->title;
        $post->body = $request->body;
        $post->image_path = $image_path;

        $post->save();

        return redirect()->route('posts.index')->with('success', '更新しました！');
    }

    public function destroy($id)
    {
        $post = Post::find($id);

        $post->delete();

        return redirect()->route('posts.index');
    }
}
