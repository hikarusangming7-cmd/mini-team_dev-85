<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Bookmark;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function store($id)
    {
        $post = Post::find($id);
        $post->bookmarks()->firstOrCreate([
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'count' => $post->bookmarks()->count(),
        ]);
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        $post->bookmarks()->where('user_id', Auth::id())->delete();

        return response()->json([
            'count' => $post->bookmarks()->count(),
        ]);
    }

}
