<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;


    protected $fillable = ['user_id','title','body','image_path'];
  
    public function comments() { return $this->hasMany(Comment::class)->latest(); }
  
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function bookmarks()
    {
        return $this->hasMany('App\Models\Bookmark');
    }

    public function bookmarkedUsers()
    {
        return $this->belongsToMany('App\Models\User', 'bookmarks');
    }

}
