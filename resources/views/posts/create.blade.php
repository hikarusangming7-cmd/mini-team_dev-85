{{-- resources/views/posts/create.blade.php (front-only demo) --}}
@extends('layouts.app')

@section('content')
<main class="container my-5">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
      <div class="card shadow-sm">
        <div class="card-body">
          <div class="d-flex align-items-center justify-content-between mb-3">
            <h1 class="h4 mb-0">画像を投稿する（デモ）</h1>
            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">戻る</a>
          </div>

          <p class="text-muted mb-4">
            JPEG / PNG / WEBP / GIF（<strong>最大 5MB/枚</strong>）。複数選択できます。
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

            {{-- 画像アップロード（ドラッグ&ドロップ + クリック） --}}
            <div class="mb-3">
              <label class="form-label">画像ファイル</label>

              <input type="file" id="images" name="images[]" class="form-control d-none"
                     accept="image/jpeg,image/png,image/webp,image/gif" multiple>

              <div id="dropzone" class="dropzone border rounded-3 p-4 text-center">
                <div class="mb-2">
                  <svg width="36" height="36" viewBox="0 0 24 24" fill="currentColor" class="text-muted">
                    <path d="M19 15v4H5v-4H3v4a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-4h-2zm-6-1 4-4h-3V3h-2v7H9l4 4z"/>
                  </svg>
                </div>
                <div class="fw-semibold">ここにドラッグ＆ドロップ</div>
                <div class="text-muted small">または <button type="button" id="pickBtn" class="btn btn-sm btn-outline-primary">ファイルを選択</button></div>
                <div class="form-text mt-2">選択後に下のプレビューへ表示されます。</div>
              </div>

              <div id="errorBox" class="alert alert-danger d-none mt-3 mb-0"></div>
            </div>

            {{-- プレビュー（Instagram 風：縦長・1列/モバイル、2列/タブレット） --}}
            <div id="previewWrap" class="row g-3 mt-3 d-none">
              {{-- JS で .col-12 .col-sm-6 内にカードを挿入 --}}
            </div>

            {{-- 操作行 --}}
            <div class="d-flex align-items-center justify-content-between mt-4">
              <div class="text-muted small" id="summaryText">0 枚選択中</div>
              <div class="d-flex gap-2">
                <button type="button" id="resetBtn" class="btn btn-outline-secondary">リセット</button>
                <button type="submit" class="btn btn-success">投稿する（デモ）</button>
              </div>
            </div>
          </form>
        </div>
      </div>

      {{-- ヒント --}}
      <div class="text-muted small mt-3">
        ※これはフロント用デモです。バックエンド実装後は、このままフォームをコントローラの <code>posts.store</code> へ向けてください。
      </div>
    </div>
  </div>
</main>

{{-- スタイル（簡易） --}}
<style>
  .dropzone {
    border-style: dashed !important;
    transition: background-color .2s, border-color .2s;
    cursor: pointer;
  }
  .dropzone.dragover {
    background-color: rgba(25, 135, 84, .06);
    border-color: #198754 !important; /* Bootstrap success */
  }
  .preview-card img {
    object-fit: cover;
    width: 100%;
    max-height: 70vh;    /* 縦長を強調（Insta 風） */
  }
  @media (max-width: 576px) {
    .preview-card img { max-height: 60vh; }
  }
</style>

{{-- デモ用 JS（バリデーション & プレビュー & ダミー送信） --}}
<script>
(() => {
  const input = document.getElementById('images');
  const dropzone = document.getElementById('dropzone');
  const pickBtn = document.getElementById('pickBtn');
  const previewWrap = document.getElementById('previewWrap');
  const errorBox = document.getElementById('errorBox');
  const resetBtn = document.getElementById('resetBtn');
  const summaryText = document.getElementById('summaryText');
  const form = document.getElementById('createForm');

  const MAX_SIZE = 5 * 1024 * 1024; // 5MB
  const ALLOW = ['image/jpeg','image/png','image/webp','image/gif'];
  let filesState = [];

  // UI helpers
  const showError = (msg) => {
    errorBox.textContent = msg;
    errorBox.classList.remove('d-none');
  };
  const clearError = () => errorBox.classList.add('d-none');

  const updateSummary = () => {
    if (!filesState.length) {
      summaryText.textContent = '0 枚選択中';
      previewWrap.classList.add('d-none');
      return;
    }
    const total = filesState.reduce((acc,f)=>acc+f.size,0);
    const mb = (total/1024/1024).toFixed(2);
    summaryText.textContent = `${filesState.length} 枚選択中（合計 ${mb} MB）`;
  };

  const renderPreviews = () => {
    previewWrap.innerHTML = '';
    if (!filesState.length) { previewWrap.classList.add('d-none'); return; }
    previewWrap.classList.remove('d-none');

    filesState.forEach((file, idx) => {
      const col = document.createElement('div');
      col.className = 'col-12 col-sm-6';

      const card = document.createElement('div');
      card.className = 'card preview-card shadow-sm';

      const img = document.createElement('img');
      img.alt = `preview ${idx+1}`;
      img.loading = 'lazy';

      const reader = new FileReader();
      reader.onload = e => img.src = e.target.result;
      reader.readAsDataURL(file);

      const body = document.createElement('div');
      body.className = 'card-body d-flex align-items-center justify-content-between';

      const meta = document.createElement('div');
      meta.className = 'small text-muted';
      meta.textContent = `${file.name}`;

      const removeBtn = document.createElement('button');
      removeBtn.type = 'button';
      removeBtn.className = 'btn btn-sm btn-outline-danger';
      removeBtn.textContent = '削除';
      removeBtn.addEventListener('click', () => {
        filesState.splice(idx, 1);
        syncInputFromState();
        renderPreviews();
        updateSummary();
      });

      body.appendChild(meta);
      body.appendChild(removeBtn);

      card.appendChild(img);
      card.appendChild(body);
      col.appendChild(card);
      previewWrap.appendChild(col);
    });
  };

  const syncInputFromState = () => {
    // FileList は直接代入できないため、新しい DataTransfer に乗せ換える
    const dt = new DataTransfer();
    filesState.forEach(f => dt.items.add(f));
    input.files = dt.files;
  };

  const validateAndAdd = (fileList) => {
    clearError();
    const accepted = [];
    for (const f of fileList) {
      if (!ALLOW.includes(f.type)) {
        showError(`未対応のファイル形式です：${f.name}`);
        continue;
      }
      if (f.size > MAX_SIZE) {
        showError(`5MBを超えるファイルはアップロードできません：${f.name}`);
        continue;
      }
      accepted.push(f);
    }
    if (!accepted.length) return;
    filesState = filesState.concat(accepted);
    syncInputFromState();
    renderPreviews();
    updateSummary();
  };

  // Events
  pickBtn.addEventListener('click', () => input.click());
  dropzone.addEventListener('click', () => input.click());

  input.addEventListener('change', (e) => validateAndAdd(e.target.files));

  ['dragenter','dragover'].forEach(evt =>
    dropzone.addEventListener(evt, (e) => {
      e.preventDefault(); e.stopPropagation();
      dropzone.classList.add('dragover');
    })
  );
  ;['dragleave','drop'].forEach(evt =>
    dropzone.addEventListener(evt, (e) => {
      e.preventDefault(); e.stopPropagation();
      dropzone.classList.remove('dragover');
    })
  );
  dropzone.addEventListener('drop', (e) => {
    const files = e.dataTransfer.files;
    if (files && files.length) validateAndAdd(files);
  });

  resetBtn.addEventListener('click', () => {
    filesState = [];
    input.value = '';
    renderPreviews();
    updateSummary();
    clearError();
  });

  // デモ送信：実際の POST は行わず、通知のみ
  form.addEventListener('submit', (e) => {
    e.preventDefault();
    const count = filesState.length;
    if (!count) {
      showError('画像を1枚以上選択してください。');
      return;
    }
    clearError();
    alert(`デモ送信：${count} 枚の画像とフォーム内容が送信される想定です。\n（バックエンド実装後に有効化してください）`);
  });

  // 初期表示
  updateSummary();
})();
</script>
@endsection
