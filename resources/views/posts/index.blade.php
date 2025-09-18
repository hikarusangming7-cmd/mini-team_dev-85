{{-- resources/views/posts/index.blade.php (front-only demo with comments) --}}
@extends('layouts.app')
@section('toolbar')
<form class="row g-2 align-items-center" method="GET" action="">
    {{-- 検索キーワード --}}
    <div class="col-12 col-md">
        <div class="input-group">
            <span class="input-group-text">🔎</span>
            <input
                type="search"
                name="q"
                value="{{ request('q') }}"
                class="form-control"
                placeholder="キーワードで探す（例：カフェ、ラン）">
        </div>
    </div>

    {{-- 並び替え（新しい順をデフォルト表示） --}}
    <div class="col-6 col-md-auto">
        <select name="sort" class="form-select">
            <option value="new" {{ request('sort','new') === 'new' ? 'selected' : '' }}>新しい順</option>
            <option value="old" {{ request('sort') === 'old' ? 'selected' : '' }}>古い順</option>
        </select>
    </div>

    {{-- ボタン群 --}}
    <div class="col-6 col-md-auto d-flex gap-2">
        <button class="btn btn-primary" type="submit">検索</button>
        <a class="btn btn-outline-secondary" href="{{ url()->current() }}">リセット</a>
    </div>
</form>
@endsection

@section('content')
@php
    $posts = collect([
        [
            'id' => 1,
            'user' => ['name' => 'Alice', 'email' => 'alice@example.com', 'avatar' => 'https://i.pravatar.cc/150?img=5'],
            'title' => '週末のカフェ巡り',
            'body' => 'ラテが最高でした☕️ #カフェ #休日',
            'created_human' => '2時間前',
            'images' => [
                'https://picsum.photos/seed/coffee1/1200/900',
                'https://picsum.photos/seed/coffee2/1200/900',
                'https://picsum.photos/seed/coffee3/1200/900',
            ],
        ],
        [
            'id' => 2,
            'user' => ['name' => 'Bob', 'email' => 'bob@example.com', 'avatar' => 'https://i.pravatar.cc/150?img=14'],
            'title' => '朝ラン',
            'body' => '川沿いの風が気持ちいい🏃‍♂️',
            'created_human' => '昨日',
            'images' => [
                'https://picsum.photos/seed/run1/1200/1500',
            ],
        ],
        [
            'id' => 3,
            'user' => ['name' => 'Carol', 'email' => 'carol@example.com', 'avatar' => 'https://i.pravatar.cc/150?img=32'],
            'title' => null,
            'body' => '新しいレンズを試写📸',
            'created_human' => '3日前',
            'images' => [
                'https://picsum.photos/seed/lens1/1200/900',
                'https://picsum.photos/seed/lens2/1200/900',
            ],
        ],
    ]);
@endphp

<main class="container my-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h4 mb-0">タイムライン（デモ）</h1>
        <a href="#" class="btn btn-success disabled" tabindex="-1" aria-disabled="true">画像を投稿する</a>
    </div>

    @if ($posts->isEmpty())
        <div class="text-center text-muted py-5">まだ投稿がありません。</div>
    @endif

    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">
            @foreach ($posts as $post)
                @php
                    $carouselId = 'postCarousel_'.$post['id'];
                    $collapseId = 'cmt_'.$post['id'];        // ← コメント欄のID
                    $formId = 'cmtForm_'.$post['id'];         // ← コメントフォームID
                    $listId = 'cmtList_'.$post['id'];         // ← コメント一覧ID
                    $countId = 'cmtCount_'.$post['id'];       // ← 件数表示ID
                @endphp

                <article class="card mb-5 shadow-sm">
                    <div class="card-header bg-white d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $post['user']['avatar'] }}" alt="{{ $post['user']['name'] }}"
                                 class="rounded-circle" width="36" height="36" loading="lazy">
                            <div class="ms-2">
                                <div class="fw-semibold">{{ $post['user']['name'] }}</div>
                                <div class="small text-muted">{{ $post['created_human'] }}</div>
                            </div>
                        </div>

                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">操作</button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item disabled" href="#" tabindex="-1" aria-disabled="true">編集（デモ）</a></li>
                                <li><a class="dropdown-item disabled text-danger" href="#" tabindex="-1" aria-disabled="true">削除（デモ）</a></li>
                            </ul>
                        </div>
                    </div>

                    @if (!empty($post['title']))
                        <div class="px-3 pt-3">
                            <h2 class="h5 mb-2">{{ $post['title'] }}</h2>
                        </div>
                    @endif

                    @if (count($post['images']) > 1)
                        <div id="{{ $carouselId }}" class="carousel slide">
                            <div class="carousel-indicators">
                                @foreach ($post['images'] as $i => $url)
                                    <button type="button" data-bs-target="#{{ $carouselId }}" data-bs-slide-to="{{ $i }}"
                                            @class(['active' => $i === 0]) aria-current="{{ $i===0?'true':'false' }}"></button>
                                @endforeach
                            </div>
                            <div class="carousel-inner">
                                @foreach ($post['images'] as $i => $url)
                                    <div @class(['carousel-item','active'=>$i===0])>
                                        <img src="{{ $url }}" class="d-block w-100" alt="post image {{ $i+1 }}"
                                             loading="lazy" style="object-fit:cover; max-height:75vh;">
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#{{ $carouselId }}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span><span class="visually-hidden">前へ</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#{{ $carouselId }}" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span><span class="visually-hidden">次へ</span>
                            </button>
                        </div>
                    @else
                        <img src="{{ $post['images'][0] }}" class="card-img-top" alt="post image"
                             loading="lazy" style="object-fit:cover; max-height:80vh;">
                    @endif

                    <div class="card-body">
                        @if (!empty($post['body']))
                            <p class="mb-3">{{ $post['body'] }}</p>
                        @endif

                        {{-- アクション行：コメントボタンで入力欄を開閉 --}}
                        <div class="d-flex align-items-center gap-3">
                            <a href="#" class="btn btn-sm btn-outline-primary disabled" tabindex="-1" aria-disabled="true">詳細を見る（デモ）</a>

                            {{-- コメントボタン（件数バッジ付き） --}}
                            <button class="btn btn-sm btn-outline-secondary"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#{{ $collapseId }}"
                                    aria-expanded="false"
                                    aria-controls="{{ $collapseId }}">
                                💬 コメント <span id="{{ $countId }}" class="badge text-bg-secondary align-middle ms-1">0</span>
                            </button>

                            <button class="btn btn-sm btn-outline-secondary" disabled>♡ いいね（デモ）</button>
                        </div>

                        {{-- コメント欄（折りたたみ） --}}
                        <div id="{{ $collapseId }}" class="collapse mt-3">
                            {{-- 既存コメント一覧（デモでは空から） --}}
                            <ul id="{{ $listId }}" class="list-unstyled mb-3 small"></ul>

                            {{-- 入力フォーム（デモ） --}}
                            <form id="{{ $formId }}" class="d-flex gap-2 align-items-start" action="#" method="POST">
                                <input type="text" name="author" class="form-control" placeholder="名前（任意）" style="max-width: 160px;">
                                <input type="text" name="text" class="form-control" placeholder="コメントを入力…">
                                <button type="submit" class="btn btn-primary">送信</button>
                            </form>
                            <div class="form-text mt-2">※デモのため、送信してもサーバ保存はされません。</div>
                        </div>
                    </div>
                </article>
            @endforeach

            <nav aria-label="pagination demo" class="d-flex justify-content-center">
                <ul class="pagination">
                    <li class="page-item disabled"><span class="page-link">«</span></li>
                    <li class="page-item active" aria-current="page"><span class="page-link">1</span></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">»</a></li>
                </ul>
            </nav>
        </div>
    </div>
</main>

<style>
@media (max-width: 576px) {
  .card-img-top, .carousel .carousel-item img { max-height: 65vh !important; }
}
.comment-item { background: #f8f9fa; border-radius: .5rem; padding: .5rem .75rem; }
.comment-meta { color: #6c757d; }
</style>

{{-- コメントのデモ挙動（追加・件数更新） --}}
<script>
(() => {
  // 投稿単位で「フォーム送信 → リストへ追加 → 件数更新」
  document.querySelectorAll('form[id^="cmtForm_"]').forEach(form => {
    form.addEventListener('submit', (e) => {
      e.preventDefault();
      const postId   = form.id.replace('cmtForm_', '');
      const list     = document.getElementById('cmtList_' + postId);
      const countTag = document.getElementById('cmtCount_' + postId);
      const author   = (form.querySelector('input[name="author"]').value || '名無しさん').trim();
      const text     = (form.querySelector('input[name="text"]').value || '').trim();
      if (!text) return;

      const li = document.createElement('li');
      li.className = 'comment-item mb-2';
      const now = new Date();
      const hh = now.getHours().toString().padStart(2,'0');
      const mm = now.getMinutes().toString().padStart(2,'0');

      li.innerHTML = `
        <div class="comment-meta small mb-1">${author} ・ ${hh}:${mm}</div>
        <div>${escapeHtml(text)}</div>
      `;
      list.appendChild(li);

      // 件数更新
      countTag.textContent = (parseInt(countTag.textContent, 10) || 0) + 1;

      // 入力クリア
      form.querySelector('input[name="text"]').value = '';
    });
  });

  function escapeHtml(str) {
    return str.replace(/[&<>"']/g, s => ({
      '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'
    }[s]));
  }
})();
</script>
@endsection
