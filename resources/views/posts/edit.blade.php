{{-- resources/views/posts/edit.blade.php (front-only demo) --}}
@extends('layouts.app')

@section('content')
    @php
        // ▼ デモ用既存投稿（コントローラから $post が来なければ使う）
        $post =
            $post ??
            (object) [
                'id' => 42,
                'title' => '週末のスナップ',
                'body' => '桜が満開でした🌸',
                // 既存画像（実装時はDBのID/パスを渡す想定）
                'images' => collect([
                    (object) ['id' => 101, 'url' => 'https://picsum.photos/seed/edit1/1200/900'],
                    (object) ['id' => 102, 'url' => 'https://picsum.photos/seed/edit2/1200/900'],
                    (object) ['id' => 103, 'url' => 'https://picsum.photos/seed/edit3/1200/1500'],
                ]),
            ];
    @endphp

    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h1 class="h4 mb-0">投稿を編集（デモ）</h1>
                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">戻る</a>
                        </div>

                        <form id="editForm" method="POST" action="#" enctype="multipart/form-data" novalidate>
                            @csrf
                            @method('PUT')

                            {{-- タイトル --}}
                            <div class="mb-3">
                                <label for="title" class="form-label">タイトル（任意）</label>
                                <input type="text" id="title" name="title" class="form-control"
                                    value="{{ old('title', $post->title) }}" placeholder="アルバム名やメモなど">
                            </div>

                            {{-- 説明 --}}
                            <div class="mb-3">
                                <label for="body" class="form-label">説明（任意）</label>
                                <textarea id="body" name="body" class="form-control" rows="3" placeholder="キャプションを編集…">{{ old('body', $post->body) }}</textarea>
                            </div>

                            {{-- 既存画像（削除トグル可能） --}}
                            <div class="mb-3">
                                <label class="form-label">既存画像</label>
                                <div id="existingWrap" class="row g-3">
                                    @foreach ($post->images as $img)
                                        <div class="col-12 col-sm-6" data-img-id="{{ $img->id }}">
                                            <div class="card shadow-sm existing-card">
                                                <img src="{{ $img->url }}" class="card-img-top"
                                                    alt="existing {{ $img->id }}" loading="lazy"
                                                    style="object-fit:cover; max-height:70vh;">
                                                <div class="card-body d-flex align-items-center justify-content-between">
                                                    <span class="small text-muted">#{{ $img->id }}</span>
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-danger toggle-delete"
                                                        data-img-id="{{ $img->id }}">削除予定にする</button>
                                                </div>
                                                {{-- 実装時は hidden name="delete_ids[]" を付け外し --}}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="form-text">赤枠になった画像は「削除予定」です（デモ）。</div>
                            </div>

                            {{-- 画像追加（ドラッグ&ドロップ + クリック） --}}
                            <div class="mb-3">
                                <label class="form-label">画像を追加</label>

                                <input type="file" id="images" name="images[]" class="form-control d-none"
                                    accept="image/jpeg,image/png,image/webp,image/gif" multiple>

                                <div id="dropzone" class="dropzone border rounded-3 p-4 text-center">
                                    <div class="mb-2">
                                        <svg width="36" height="36" viewBox="0 0 24 24" fill="currentColor"
                                            class="text-muted">
                                            <path
                                                d="M19 15v4H5v-4H3v4a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-4h-2zm-6-1 4-4h-3V3h-2v7H9l4 4z" />
                                        </svg>
                                    </div>
                                    <div class="fw-semibold">ここにドラッグ＆ドロップ</div>
                                    <div class="text-muted small">または <button type="button" id="pickBtn"
                                            class="btn btn-sm btn-outline-primary">ファイルを選択</button></div>
                                    <div class="form-text mt-2">JPEG / PNG / WEBP / GIF（最大 5MB/枚）</div>
                                </div>

                                <div id="errorBox" class="alert alert-danger d-none mt-3 mb-0"></div>
                            </div>

                            {{-- 追加画像プレビュー --}}
                            <div id="previewWrap" class="row g-3 mt-3 d-none"></div>

                            {{-- 操作行 --}}
                            <div class="d-flex align-items-center justify-content-between mt-4">
                                <div class="small text-muted" id="summaryText">追加 0 枚・削除 0 枚</div>
                                <div class="d-flex gap-2">
                                    <a href="#" class="btn btn-outline-secondary"
                                        onclick="history.back(); return false;">キャンセル</a>
                                    <button type="submit" class="btn btn-success">更新する（デモ）</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-muted small mt-3">
                    {{-- ※フロント用デモです。実装時は <code>action="{{ route('posts.update', $post->id) }}"</code> に変更し、
        JS の送信キャンセルを外してください。 --}}
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
            border-color: #198754 !important;
        }

        .existing-card.deleting {
            border: 2px solid #dc3545;
        }

        .preview-card img,
        .existing-card img {
            width: 100%;
            object-fit: cover;
            max-height: 70vh;
        }

        @media (max-width: 576px) {

            .preview-card img,
            .existing-card img {
                max-height: 60vh;
            }
        }
    </style>

    {{-- デモ用 JS（既存画像の削除トグル / 追加画像のプレビュー / バリデーション / ダミー送信） --}}
    <script>
        (() => {
            const MAX_SIZE = 5 * 1024 * 1024;
            const ALLOW = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];

            // 既存画像の delete 状態（実装時は hidden input に反映）
            const deleteSet = new Set();

            // Elements
            const existingWrap = document.getElementById('existingWrap');
            const input = document.getElementById('images');
            const dropzone = document.getElementById('dropzone');
            const pickBtn = document.getElementById('pickBtn');
            const previewWrap = document.getElementById('previewWrap');
            const errorBox = document.getElementById('errorBox');
            const summaryText = document.getElementById('summaryText');
            const form = document.getElementById('editForm');

            let filesState = [];

            // --- helpers ---
            const showError = (msg) => {
                errorBox.textContent = msg;
                errorBox.classList.remove('d-none');
            };
            const clearError = () => errorBox.classList.add('d-none');

            const updateSummary = () => {
                summaryText.textContent = `追加 ${filesState.length} 枚・削除 ${deleteSet.size} 枚`;
                previewWrap.classList.toggle('d-none', filesState.length === 0);
            };

            const syncInputFromState = () => {
                const dt = new DataTransfer();
                filesState.forEach(f => dt.items.add(f));
                input.files = dt.files;
            };

            const renderPreviews = () => {
                previewWrap.innerHTML = '';
                filesState.forEach((file, idx) => {
                    const col = document.createElement('div');
                    col.className = 'col-12 col-sm-6';
                    const card = document.createElement('div');
                    card.className = 'card preview-card shadow-sm';
                    const img = document.createElement('img');
                    img.alt = `new ${idx+1}`;
                    img.loading = 'lazy';
                    const reader = new FileReader();
                    reader.onload = e => img.src = e.target.result;
                    reader.readAsDataURL(file);

                    const body = document.createElement('div');
                    body.className = 'card-body d-flex align-items-center justify-content-between';
                    const meta = document.createElement('div');
                    meta.className = 'small text-muted';
                    meta.textContent = file.name;
                    const rm = document.createElement('button');
                    rm.type = 'button';
                    rm.className = 'btn btn-sm btn-outline-danger';
                    rm.textContent = '削除';
                    rm.addEventListener('click', () => {
                        filesState.splice(idx, 1);
                        syncInputFromState();
                        renderPreviews();
                        updateSummary();
                    });

                    body.appendChild(meta);
                    body.appendChild(rm);
                    card.appendChild(img);
                    card.appendChild(body);
                    col.appendChild(card);
                    previewWrap.appendChild(col);
                });
            };

            const validateAndAdd = (fileList) => {
                clearError();
                const accepted = [];
                for (const f of fileList) {
                    if (!ALLOW.includes(f.type)) {
                        showError(`未対応の形式：${f.name}`);
                        continue;
                    }
                    if (f.size > MAX_SIZE) {
                        showError(`5MB超過：${f.name}`);
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

            // --- existing toggle delete ---
            existingWrap?.addEventListener('click', (e) => {
                const btn = e.target.closest('.toggle-delete');
                if (!btn) return;
                const id = btn.dataset.imgId;
                const card = existingWrap.querySelector(`[data-img-id="${id}"] .existing-card`) ||
                    btn.closest('.existing-card');

                if (deleteSet.has(id)) {
                    deleteSet.delete(id);
                    btn.textContent = '削除予定にする';
                    card?.classList.remove('deleting');
                } else {
                    deleteSet.add(id);
                    btn.textContent = '削除予定を解除';
                    card?.classList.add('deleting');
                }
                updateSummary();
            });

            // --- add images (drop/choose) ---
            pickBtn.addEventListener('click', () => input.click());
            dropzone.addEventListener('click', () => input.click());
            input.addEventListener('change', (e) => validateAndAdd(e.target.files));

            ['dragenter', 'dragover'].forEach(evt => dropzone.addEventListener(evt, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.add('dragover');
            }));;
            ['dragleave', 'drop'].forEach(evt => dropzone.addEventListener(evt, (e) => {
                e.preventDefault();
                e.stopPropagation();
                dropzone.classList.remove('dragover');
            }));
            dropzone.addEventListener('drop', (e) => {
                const files = e.dataTransfer.files;
                if (files?.length) validateAndAdd(files);
            });

            // --- demo submit ---
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                clearError();
                alert(
                    `デモ送信：\n` +
                    `・タイトル：${document.getElementById('title').value}\n` +
                    `・本文：${document.getElementById('body').value}\n` +
                    `・追加画像：${filesState.length} 枚\n` +
                    `・削除予定：${deleteSet.size} 枚（IDs: ${[...deleteSet].join(', ') || 'なし'}）\n\n` +
                    `バックエンド実装後に実際の PUT を行ってください。`
                );
            });

            // init
            updateSummary();
        })();
    </script>
@endsection
