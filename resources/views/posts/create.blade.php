{{-- resources/views/posts/create.blade.php --}}
@extends('layouts.app')

@section('content')
<main class="container my-5">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
      <div class="card shadow-sm">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <h1 class="h4 mb-0">日記を投稿する</h1>
            <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">戻る</a>
          </div>

          <form id="createForm" method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data" novalidate>
            @csrf

            <div class="mb-3">
              <label for="title" class="form-label">タイトル</label>
              <input type="text" id="title" name="title" class="form-control @error('title') is-invalid @enderror" placeholder="タイトルを入力" value="{{ old('title') }}">
              @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
              <label for="body" class="form-label">コンテント</label>
              <textarea id="body" name="body" class="form-control @error('body') is-invalid @enderror" rows="3" placeholder="コンテントを入力">{{ old('body') }}</textarea>
              @error('body') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
              <label class="form-label">画像ファイル</label>
              <p class="text-muted mb-2">JPEG / JPG / PNG（最大 2MB）</p>
              <input type="file" id="image" name="photo" class="form-control @error('photo') is-invalid @enderror" accept="image/jpeg,image/png,image/jpg">
              @error('photo') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mt-4 text-end">
              <button type="submit" class="btn btn-success">投稿する</button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</main>
@endsection
