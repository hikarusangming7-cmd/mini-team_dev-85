@extends('layouts.app')
@section('content')

    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card">
                    <div class="card-body">

                        <h1 class="h4 mb-3">画像を投稿する</h1>
                        <p class="text-muted mb-4">JPEG / PNG / WEBP / GIF（最大 5MB/枚）。複数選択可。</p>

                        <form method="POST" action="" enctype="multipart/form-data" class="mb-3">
                            @csrf

                            <div class="mb-3">
                                <label for="title" class="form-label">タイトル（任意）</label>
                                <input type="text" id="title" name="title" class="form-control"
                                    placeholder="アルバム名やメモなど">
                            </div>

                            <div class="mb-3">
                                <label for="images" class="form-label">画像ファイル</label>
                                <input type="file" id="images" name="images[]" class="form-control" accept="image/*"
                                    multiple required>
                                <div class="form-text">複数枚をまとめて選択できます。</div>
                            </div>

                            <button type="submit" class="btn btn-success">投稿する</button>
                            <button type="reset" class="btn btn-outline-secondary">リセット</button>
                        </form>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('status'))
                            <div class="alert alert-success mt-3">
                                {{ session('status') }}
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
