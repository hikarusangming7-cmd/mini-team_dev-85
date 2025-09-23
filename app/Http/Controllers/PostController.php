<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    public function index()
    {
        $posts = Post::with(['user:id,name'])
            ->withCount('comments')
            ->latest()
            ->get();

        return view('posts.index', ['posts' => $posts]);
    }

    public function create() { return view('posts.create'); }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:30',
            'body'  => 'required|string|max:140',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $image_path = $request->file('photo')->store('photos', 'public');

        $post = new Post();
        $post->fill([
            'title'      => $request->title,
            'body'       => $request->body,
            'image_path' => $image_path,
            'user_id'    => Auth::id(),
        ])->save();

        return redirect()->route('posts.index')->with('success', '投稿しました！');
    }

    public function edit($id)   { return view('posts.edit', ['post' => Post::findOrFail($id)]); }
    public function update(Request $req, $id)
    {
        $post = Post::findOrFail($id);
        $req->validate([
            'title' => 'required|string|max:30',
            'body'  => 'required|string|max:140',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $image_path = $post->image_path;
        if ($req->hasFile('photo')) {
            $image_path = $req->file('photo')->store('photos', 'public');
        }

        $post->update([
            'title'      => $req->title,
            'body'       => $req->body,
            'image_path' => $image_path,
        ]);

        return redirect()->route('posts.index')->with('success', '更新しました！');
    }

    public function destroy($id)
    {
        Post::findOrFail($id)->delete();
        return redirect()->route('posts.index');
    }
}
