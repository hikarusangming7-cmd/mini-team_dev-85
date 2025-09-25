{{-- resources/views/posts/edit.blade.php (front-only demo) --}}
@extends('layouts.app')

@section('content')
    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h1 class="h4 mb-0">投稿を編集</h1>
                        </div>

                        <form id="editForm" method="POST" action="{{ route('posts.update', $post->id) }}"
                            enctype="multipart/form-data" novalidate>
                            @csrf
                            @method('PUT')

                            {{-- タイトル --}}
                            <div class="mb-3">
                                <label for="title" class="form-label">タイトル</label>
                                <input type="text" id="title" name="title"
                                    class="form-control @error('title') is-invalid @enderror"
                                    value="{{ old('title', $post->title) }}" placeholder="タイトルを入力">
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- 説明 --}}
                            <div class="mb-3">
                                <label for="body" class="form-label">コンテント</label>
                                <textarea id="body" name="body" class="form-control @error('body') is-invalid @enderror" rows="3"
                                    placeholder="コンテントを入力">{{ old('body', $post->body) }}</textarea>
                                @error('body')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- 既存画像（1枚前提 / チェックで削除指定） --}}
                            <div class="mb-3">
                                <label class="form-label">既存画像</label>
                                <div class="card shadow-sm">
                                    <img src="{{ asset('storage/' . $post->image_path) }}" class="card-img-top"
                                        loading="lazy" style="object-fit:cover; max-height:100%;">
                                    <div class="card-body d-flex align-items-center justify-content-between">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="delete_image"
                                                value="1" id="del_single">
                                            <label class="form-check-label" for="del_single">この画像を削除する</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-text">画像を変更する場合はチェックを入れ新規画像を登録してください。</div>
                            </div>

                            {{-- 画像追加フォーム（最初は非表示） --}}
                            <div class="mb-3 d-none" id="imageUploadArea">
                                <label class="form-label">新しい画像を登録</label>
                                <input type="file" id="image" name="photo"
                                    class="form-control @error('photo') is-invalid @enderror"
                                    accept="image/jpeg,image/png,image/jpg">
                                <div class="form-text">JPEG / PNG / JPG（最大 2MB）</div>
                                @error('photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- 操作行 --}}
                            <div class="mt-4 d-flex justify-content-end gap-2">
                                <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">キャンセル</a>
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

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkbox = document.getElementById('del_single');
            const uploadArea = document.getElementById('imageUploadArea');

            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    uploadArea.classList.remove('d-none');
                } else {
                    uploadArea.classList.add('d-none');
                }
            });
        });
    </script>
@endpush
