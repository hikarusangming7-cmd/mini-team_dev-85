<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // 一覧（JSON）
    public function index(Post $post)
    {
        $comments = $post->comments()
            ->with('user:id,name')
            ->get()
            ->map(fn ($c) => [
                'id'   => $c->id,
                'name' => $c->user->name ?? ($c->author_name ?: '名無しさん'),
                'body' => $c->body,
                'time' => $c->created_at->diffForHumans(),
            ]);

        return response()->json([
            'total'    => $post->comments()->count(),
            'comments' => $comments,
        ]);
    }

    // 登録（JSON）
    public function store(Request $request, Post $post)
    {
        $data = $request->validate([
            'author_name' => ['nullable','string','max:40'],
            'body'        => ['required','string','max:500'],
        ]);

        $c = $post->comments()->create([
            'user_id'     => auth()->id(),
            'author_name' => $data['author_name'] ?? null,
            'body'        => $data['body'],
        ]);

        return response()->json([
            'comment' => [
                'id'   => $c->id,
                'name' => auth()->user()->name ?? ($c->author_name ?: '名無しさん'),
                'body' => $c->body,
                'time' => $c->created_at->diffForHumans(),
            ],
            'total'   => $post->comments()->count(),
        ], 201);
    }
}
