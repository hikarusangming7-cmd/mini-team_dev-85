<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

Route::get('/', fn () => view('welcome'));

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Posts
Route::get('/posts',              [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/create',       [PostController::class, 'create'])->name('posts.create');
Route::post('/posts',             [PostController::class, 'store'])->name('posts.store');
Route::get('/posts/edit/{id}',    [PostController::class, 'edit'])->name('posts.edit');
Route::put('/posts/{id}',         [PostController::class, 'update'])->name('posts.update');
Route::delete('/posts/{id}',      [PostController::class, 'destroy'])->name('posts.destroy');

// Comments（AJAX：重複なし）
Route::get('/posts/{post}/comments',  [CommentController::class, 'index'])->name('posts.comments.index');
Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('posts.comments.store');
