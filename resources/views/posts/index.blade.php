@extends('layouts.app')
@section('toolbar')
    <form class="row g-2 align-items-center" method="GET" action="">
        {{-- 検索キーワード --}}
        <div class="col-12 col-md">
            <div class="input-group">
                <span class="input-group-text">🔎</span>
                <input type="search" name="q" value="{{ request('q') }}" class="form-control"
                    placeholder="キーワードで探す（例：カフェ、ラン）">
            </div>
        </div>

        {{-- 並び替え（新しい順をデフォルト表示） --}}
        <div class="col-6 col-md-auto">
            <select name="sort" class="form-select">
                <option value="new" {{ request('sort', 'new') === 'new' ? 'selected' : '' }}>新しい順</option>
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


    <main class="container my-5">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h1 class="h4 mb-0">みんなの日記</h1>
            <a href="{{ route('posts.create')}}" class="btn btn-success" >日記を投稿する</a>
        </div>

        @if ($posts->isEmpty())
            <div class="text-center text-muted py-5">まだ投稿がありません。</div>
        @endif

        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">
                @foreach ($posts as $post)
                <article class="card w-100 mb-5 shadow-sm">
                {{-- 投稿者情報をカードヘッダーに移動 --}}
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <div>
                            <div class="fw-semibold">{{ $post->user->name }}</div>
                            <div class="small text-muted">{{ $post->updated_at->format('Y/m/d H:i') }}</div>
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                type="button" data-bs-toggle="dropdown"></button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('posts.edit', $post) }}">編集</a></li>
                                <li>
                                    <form action="{{ route('posts.destroy', $post) }}" method="POST"
                                      onsubmit="return confirm('削除してよろしいですか？')">
                                    @csrf
                                    @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">削除</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>

                {{-- 投稿画像 --}}
                    <img src="{{ asset('storage/' . $post->image_path) }}"
                     class="card-img-top w-100"
                     alt="post image"
                     style="object-fit: cover; max-height: 100%;">

                {{-- 本文 --}}
                    <div class="card-body">
                        @if (!empty($post->title))
                            <h5 class="card-title fw-bold">{{ $post->title }}</h5>
                        @endif
                        <p class="card-text">{{ $post->body }}</p>
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

            .card-img-top,
            .carousel .carousel-item img {
                max-height: 65vh !important;
            }
        }

        .comment-item {
            background: #f8f9fa;
            border-radius: .5rem;
            padding: .5rem .75rem;
        }

        .comment-meta {
            color: #6c757d;
        }

    </style>

    {{-- コメントのデモ挙動（追加・件数更新） --}}
    {{-- <script>
        (() => {
            // 投稿単位で「フォーム送信 → リストへ追加 → 件数更新」
            document.querySelectorAll('form[id^="cmtForm_"]').forEach(form => {
                form.addEventListener('submit', (e) => {
                    e.preventDefault();
                    const postId = form.id.replace('cmtForm_', '');
                    const list = document.getElementById('cmtList_' + postId);
                    const countTag = document.getElementById('cmtCount_' + postId);
                    const author = (form.querySelector('input[name="author"]').value || '名無しさん').trim();
                    const text = (form.querySelector('input[name="text"]').value || '').trim();
                    if (!text) return;

                    const li = document.createElement('li');
                    li.className = 'comment-item mb-2';
                    const now = new Date();
                    const hh = now.getHours().toString().padStart(2, '0');
                    const mm = now.getMinutes().toString().padStart(2, '0');

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
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;'
                } [s]));
            }
        })();
    </script> --}}
@endsection
