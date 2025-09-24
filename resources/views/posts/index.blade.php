@extends('layouts.app')

@section('content')
<main class="container my-5">
  <div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="h4 mb-0">みんなの日記</h1>
    <a href="{{ route('posts.create') }}" class="btn btn-success">日記を投稿する</a>
  </div>

  @if ($posts->isEmpty())
    <div class="text-center text-muted py-5">まだ投稿がありません。</div>
  @endif

  <div class="row justify-content-center">
    <div class="col-lg-7 col-md-9">
      @foreach ($posts as $post)
      <article class="card w-100 mb-5 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
          <div>
            <div class="fw-semibold">{{ $post->user->name }}</div>
            <div class="small text-muted">{{ $post->updated_at->format('Y/m/d H:i') }}</div>
          </div>
          <div class="dropdown">
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"></button>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="{{ route('posts.edit', $post) }}">編集</a></li>
              <li>
                <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('削除してよろしいですか？')">
                  @csrf @method('DELETE')
                  <button type="submit" class="dropdown-item text-danger">削除</button>
                </form>
              </li>
            </ul>
          </div>
        </div>

        <img src="{{ asset('storage/' . $post->image_path) }}" class="card-img-top w-100" alt="post image" style="object-fit:cover; max-height:100%;">

        <div class="card-body">
          @if (!empty($post->title))
            <h5 class="card-title fw-bold">{{ $post->title }}</h5>
          @endif
          <p class="card-text">{{ $post->body }}</p>
        </div>

        <div class="d-flex align-items-center gap-3 px-3 pb-3">
          <button class="btn btn-sm btn-outline-secondary js-cmt-toggle"
                  type="button"
                  data-bs-toggle="collapse"
                  data-bs-target="#cmt_{{ $post->id }}"
                  data-post-id="{{ $post->id }}">
            💬 コメント
            <span class="badge text-bg-secondary align-middle ms-1" id="cmtCount-{{ $post->id }}">
              {{ $post->comments_count ?? 0 }}
            </span>
          </button>
          <button class="btn btn-sm btn-outline-secondary" disabled>♡ いいね（デモ）</button>
        </div>

        <div id="cmt_{{ $post->id }}" class="collapse px-3 pb-3">
          <ul class="list-unstyled mb-3 small" id="cmtList-{{ $post->id }}"></ul>

          <form class="d-flex gap-2 align-items-start js-cmt-form"
                data-post-id="{{ $post->id }}"
                action="{{ route('posts.comments.store', $post) }}"
                method="POST">
            @csrf
            <input type="text" name="author_name" class="form-control" placeholder="名前（任意）" style="max-width:160px;">
            <input type="text" name="body" class="form-control" placeholder="コメントを入力…">
            <button type="submit" class="btn btn-primary">送信</button>
          </form>


          <div class="form-text mt-2">※ページ遷移せずに投稿・表示されます。</div>
        </div>
      </article>
      @endforeach
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

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".like-btn").forEach(function (btn) {
                btn.addEventListener("click", function () {
                    const postId = btn.dataset.postId;
                    const countSpan = btn.querySelector(".like-count");
                    const liked = btn.classList.contains("btn-danger");
                    const url = liked
                        ? `/posts/${postId}/bookmark`
                        : `/posts/${postId}/bookmark`;

                    fetch(url, {
                        method: liked ? "DELETE" : "POST",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                            "Accept": "application/json",
                        },
                    })
                    .then(res => res.json())
                    .then(data => {
                        countSpan.textContent = data.count;

                        if (liked) {
                            btn.classList.remove("btn-danger");
                            btn.classList.add("btn-outline-secondary");
                        } else {
                            btn.classList.remove("btn-outline-secondary");
                            btn.classList.add("btn-danger");
                        }
                    });
                });
            });
        });

    </script>
@endsection

@push('scripts')
<script>
(() => {
  const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  // 「開いた時」に初回ロード（無駄なGETを避ける）
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
    const author = form.querySelector('input[name="author_name"]').value.trim();
    if (!body) return;

    try {
      const res = await fetch(`/posts/${postId}/comments`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json', 'Content-Type': 'application/json' },
        body: JSON.stringify({ body, author_name: author })
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
