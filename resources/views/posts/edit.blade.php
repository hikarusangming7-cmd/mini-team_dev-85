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
                            <h1 class="h4 mb-0">投稿を編集</h1>
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

                            {{-- 既存画像（1枚前提 / チェックで削除指定） --}}
                            <div class="mb-3">
                                <label class="form-label">既存画像（1枚）</label>
                                @php
                                    $existing = $post->images->first() ?? null; // コレクション想定。実装時は $post->image 等に合わせてください。
                                @endphp

                                @if ($existing)
                                    <div class="card shadow-sm">
                                        <img src="{{ $existing->url }}" class="card-img-top" alt="existing {{ $existing->id ?? 'image' }}" loading="lazy" style="object-fit:cover; max-height:60vh;">
                                        <div class="card-body d-flex align-items-center justify-content-between">
                                            <span class="small text-muted">#{{ $existing->id ?? '—' }}</span>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="delete_image" value="1" id="del_single">
                                                <label class="form-check-label" for="del_single">この画像を削除する</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-text">チェックを入れると現在の画像を削除します（1枚のみ）。</div>
                                @else
                                    <div class="text-muted">現在、登録済みの画像はありません。</div>
                                @endif
                            </div>

                            {{-- 画像追加（シンプルなファイル選択） --}}
                            <div class="mb-3">
                                <label class="form-label">画像を変更</label>
                                <input type="file" id="images" name="images[]" class="form-control" accept="image/jpeg,image/png,image/webp,image/gif">
                                <div class="form-text">JPEG / PNG / WEBP / GIF（最大 5MB）。※1枚のみアップロード可能です。</div>
                            </div>

                            {{-- 操作行 --}}
                            <div class="mt-4 d-flex justify-content-end gap-2">
                                <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">キャンセル</a>
                                <button type="submit" class="btn btn-success">更新する</button>
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
@endsection
