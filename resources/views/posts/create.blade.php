{{-- resources/views/posts/create.blade.php (front-only demo) --}}
@extends('layouts.app')

@section('content')
<main class="container my-5">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
      <div class="card shadow-sm">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <h1 class="h4 mb-0">日記を投稿する</h1>
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">戻る</a>
          </div>

          <p class="text-muted mb-4">
            JPEG / PNG / WEBP / GIF（<strong>最大 5MB/枚</strong>）。
          </p>

          <form id="createForm" method="POST" action="#" enctype="multipart/form-data" novalidate>
            @csrf

            {{-- タイトル --}}
            <div class="mb-3">
              <label for="title" class="form-label">タイトル（任意）</label>
              <input type="text" id="title" name="title" class="form-control" placeholder="アルバム名やメモなど">
            </div>

            {{-- 説明文（任意） --}}
            <div class="mb-3">
              <label for="body" class="form-label">説明（任意）</label>
              <textarea id="body" name="body" class="form-control" rows="3" placeholder="キャプションやメモを追加…"></textarea>
            </div>

            {{-- 画像アップロード（シンプルなファイル選択） --}}
            <div class="mb-3">
              <label class="form-label">画像ファイル</label>

              <input type="file" id="images" name="images[]" class="form-control"
                     accept="image/jpeg,image/png,image/webp,image/gif" multiple>
              <div class="form-text">JPEG / PNG / WEBP / GIF（最大 5MB/枚）。</div>
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
