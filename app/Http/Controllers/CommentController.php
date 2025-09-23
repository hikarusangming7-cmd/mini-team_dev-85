<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * 指定Postのコメント一覧をJSONで返す
     * 返却: { total: int, comments: [{id,name,body,time}] }
     */
    public function index(Post $post)
    {
        $comments = $post->comments()
            ->with('user:id,name')
            ->latest()
            ->get()
            ->map(fn($c) => [
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

    /**
     * コメント登録（ページ遷移なし）。JSON返却でその場反映。
     * 返却: { comment: {id,name,body,time}, total: int }
     */
    public function store(Request $request, Post $post)
    {
        $data = $request->validate([
            'author_name' => ['nullable','string','max:40'],
            'body'        => ['required','string','max:500'],
        ]);

        $c = Comment::create([
            'post_id'     => $post->id,
            'user_id'     => auth()->id(),                   // ゲスト可ならnullableのままでOK
            'author_name' => $data['author_name'] ?? null,
            'body'        => $data['body'],
        ]);

        $payload = [
            'id'   => $c->id,
            'name' => auth()->user()->name ?? ($c->author_name ?: '名無しさん'),
            'body' => $c->body,
            'time' => $c->created_at->diffForHumans(),
        ];

        return response()->json([
            'comment' => $payload,
            'total'   => $post->comments()->count(),
        ], 201);
    }
}
