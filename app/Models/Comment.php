<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    // ← ここに追加（クラスの中、メソッドの外）
    protected $fillable = [
        'post_id',
        'user_id',      // ログインユーザーを紐づけるなら必要（任意）
        'author_name',
        'body',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function post()
    {
        return $this->belongsTo(\App\Models\Post::class);
    }
}
