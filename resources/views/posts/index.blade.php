
    @extends('layouts.app')
    @section('toolbar')
        <form class="row g-2 align-items-center" method="GET" action="{{ route('posts.index') }}">
        {{-- 検索キーワード --}}
        <div class="col-12 col-md">
        <div class="input-group">
        <span class="input-group-text">🔎</span>
        <input type="search" name="q" value="{{ request('q') }}" class="form-control"
        placeholder="キーワードで探す（投稿者名、タイトル など）">

        {{-- 並び替え --}}
        <div class="col-6 col-md-auto" style="margin: 0 10px">
            <select name="sort" class="form-select" onchange="this.form.submit()">
                <option value="new" {{ request('sort', 'new') === 'new' ? 'selected' : '' }} >新しい順</option>
                <option value="old" {{ request('sort') === 'old' ? 'selected' : '' }}>古い順</option>
            </select>
        </div>

        {{-- hidden で filter を保持 --}}
        <input type="hidden" name="filter" value="{{ request('filter') }}">

        {{-- ボタン群 --}}
        <div class="col-6 col-md-auto d-flex gap-2">
            @php($filterActive = request('filter') === 'bookmarked')
            <button
                type="submit"
                name="filter"
                value="{{ $filterActive ? '' : 'bookmarked' }}"
                class="btn btn-bookmark {{ $filterActive ? 'active' : '' }}">
                ♡
            </button>
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
                            @if ($post->user_id === Auth::id())
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
                            @endif
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
                        <div class="d-flex align-items-center gap-3 px-3 pb-3">
                            <button class="btn btn-sm btn-outline-secondary js-cmt-toggle" type="button"
                            data-bs-toggle="collapse" data-bs-target="#cmt_{{ $post->id }}"
                            data-post-id="{{ $post->id }}">
                            💬 コメント
                            <span class="badge text-bg-secondary align-middle ms-1" id="cmtCount-{{ $post->id }}">
                                {{ $post->comments_count ?? 0 }}
                            </span>
                            </button>
                            <button class="like-btn btn btn-sm {{ $post->bookmarks->contains('user_id', Auth::id()) ? 'btn-danger' : 'btn-outline-secondary' }}"
                                data-post-id="{{ $post->id }}">♡ <span class="like-count">{{ $post->bookmarks->count() }}</span>
                            </button>
                        </div>

                        <div id="cmt_{{ $post->id }}" class="collapse px-3 pb-3">
                        <ul class="list-unstyled mb-3 small" id="cmtList-{{ $post->id }}"></ul>

                        <form class="d-flex gap-2 align-items-start js-cmt-form"
                                data-post-id="{{ $post->id }}"
                                action="{{ route('posts.comments.store', $post) }}"
                                method="POST">
                            @csrf
                            {{-- <input type="text" name="author_name" class="form-control" placeholder="名前" style="max-width:160px;"> --}}
                        <input type="text" name="body" class="form-control" placeholder="コメントを入力…">
                        <button type="submit" class="btn btn-primary" style="height: 36.36px; display:inline-block; writing-mode:horizontal-tb; transform:none; white-space:nowrap;">送信</button>
                        </form>


                    </div>

                    </article>
                    @endforeach
                </div>
            </div>
        </main>
        @endsection

        @push('styles')
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

            .btn-bookmark {
                background-color: white !important;
                color: red !important;
                border: 1px solid red !important;
                box-shadow: none !important;
            }

            .btn-bookmark.active {
                background-color: red !important;
                color: white !important;
                border: none
            }

            /* hover/focusでも色を変えない */
            #btn-bookmark:hover,
            #btn-bookmark:focus,
            #btn-bookmark:active {
                background-color: inherit !important;
                color: inherit !important;
                box-shadow: none !important;
            }

        </style>
        @endpush

        @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                document.querySelectorAll(".like-btn").forEach(function (btn) {
                    btn.addEventListener("click", function () {
                        const postId = btn.dataset.postId;
                        const countSpan = btn.querySelector(".like-count");
                        const liked = btn.classList.contains("btn-danger");
                        const url = `/posts/${postId}/bookmark`;

                        fetch(url, {
                            method: liked ? "DELETE" : "POST",
                            headers: {
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                                "Accept": "application/json",
                            },
                        })
                        .then(res => {
                            if (!res.ok) throw new Error('Network response was not ok');
                            return res.json();
                        })
                        .then(data => {
                            countSpan.textContent = data.count;
                            if (liked) {
                                btn.classList.remove("btn-danger");
                                btn.classList.add("btn-outline-secondary");
                            } else {
                                btn.classList.remove("btn-outline-secondary");
                                btn.classList.add("btn-danger");
                            }
                        })
                        .catch(err => console.error(err));
                    });
                });
            });

            // コメント周り（IIFE）
            (() => {
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                // ——— プリロード関数 ———
                function preloadComments() {
                    // コメントボタンが付いている投稿だけプリロードする
                    document.querySelectorAll('.js-cmt-toggle').forEach(btn => {
                        const postId = btn.dataset.postId;
                        if (!postId) return;
                        const listEl = document.getElementById('cmtList-' + postId);
                        if (listEl && !listEl.dataset.loaded) {
                            // force=true で強制ロード（初回プリロード）
                            loadComments(postId, true).catch(e => console.error(e));
                        }
                    });
                }

                // DOM の状態に応じて即時 or 後でプリロード
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', preloadComments);
                } else {
                    preloadComments();
                }

                // 「開いた時」にもフォールバックで読み込む（既に loaded なら実際の再取得はしない）
                document.querySelectorAll('.js-cmt-toggle').forEach(btn => {
                    const target = document.querySelector(btn.dataset.bsTarget || btn.getAttribute('data-bs-target'));
                    if (!target) return;
                    target.addEventListener('shown.bs.collapse', () => loadComments(btn.dataset.postId));
                });

                // 送信（リロードしない）— イベント委譲で重複防止
                document.addEventListener('submit', async (e) => {
                    const form = e.target;
                    if (!form.classList.contains('js-cmt-form')) return;
                    e.preventDefault();

                    const postId = form.dataset.postId;
                    const listEl = document.getElementById('cmtList-' + postId);
                    const body   = form.querySelector('input[name="body"]').value.trim();
                    if (!body) return;

                    try {
                        const res = await fetch(`/posts/${postId}/comments`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ body })
                        });
                        if (!res.ok) throw new Error('failed to post');
                        const data  = await res.json();
                        const badge = document.getElementById('cmtCount-' + postId);

                        if (!listEl.dataset.loaded) {
                            await loadComments(postId, true);
                        } else {
                            appendComment(listEl, data.comment);
                        }
                        if (badge && typeof data.total === 'number') badge.textContent = data.total;
                        form.reset();
                    } catch (err) {
                        console.error(err);
                        alert('コメントの投稿に失敗しました');
                    }
                });

                // コメント取得（force=true でキャッシュ無視）
                async function loadComments(postId, force = false) {
                    const listEl = document.getElementById('cmtList-' + postId);
                    if (!listEl) return;
                    if (listEl.dataset.loaded && !force) return;

                    try {
                        const res = await fetch(`/posts/${postId}/comments`, { headers: { 'Accept': 'application/json' } });
                        if (!res.ok) throw new Error('failed to load');
                        const data      = await res.json();
                        const comments  = Array.isArray(data) ? data : (data.comments || []);
                        const total     = Array.isArray(data) ? comments.length : (data.total ?? comments.length);

                        listEl.innerHTML = '';
                        comments.forEach(c => appendComment(listEl, c));
                        listEl.dataset.loaded = '1';

                        const badge = document.getElementById('cmtCount-' + postId);
                        if (badge) badge.textContent = total;
                    } catch (e) {
                        console.error(e);
                    }
                }

                function appendComment(listEl, c) {
                    const li = document.createElement('li');
                    li.className = 'comment-item mb-2';
                    li.innerHTML = `
                        <div class="comment-meta small mb-1">${escapeHtml(c.name)} ・ ${escapeHtml(c.time)}</div>
                        <div>${escapeHtml(c.body)}</div>
                    `;
                    listEl.prepend(li);
                }

                function escapeHtml(str = '') {
                    return str.replace(/[&<>"']/g, s => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[s]));
                }
            })();

        </script>
        @endpush
