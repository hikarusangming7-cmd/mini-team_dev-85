<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/posts', function () {
    return view('posts.index');   // resources/views/posts/index.blade.php
});

Route::get('/posts/create', function () {
    return view('posts.create');  // resources/views/posts/create.blade.php
});

Route::get('/posts/edit', function () {
    return view('posts.edit');  // resources/views/posts/create.blade.php
});

Route::get('/posts/show', function () {
    return view('posts.show');  // resources/views/posts/create.blade.php
});


Auth::routes();

