@extends('layouts.app')
@section('content')

    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="card">
                    <div class="card-body">

                        @php
                            // UIのみ（仮データ）
                            $title = 'デモ投稿タイトル';
                            $body  = 'これはデモ本文です。ここに投稿の説明やメモを表示します。UIプレビュー用の仮テキストです。';
                            $image = 'https://placehold.co/1200x800?text=Demo+Image';
                        @endphp

                        <div class="mx-auto" style="max-width: 720px;">
                            <h1 class="h4 mb-3">{{ $title }}</h1>
                            <p class="mb-3 text-muted">{{ $body }}</p>
                            <img src="{{ $image }}" class="card-img-top mb-3" alt="demo image"
                                 loading="lazy" style="object-fit:cover; max-height:80vh;">
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="" class="btn btn-outline-secondary">戻る</a>
                            <div>
                                <a href="" class="btn btn-primary me-2">編集</a>
                                <form action="" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('本当に削除しますか？');">削除</button>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
