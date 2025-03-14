@extends('layouts.site')
@section('title', $post->title)
@section('content')
    <section class="hdl-maincontent py-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="post-detail">
                        <div class="post-image text-center mb-4">
                            <img src="{{ asset('images/posts/' . $post->image) }}" alt="{{ $post->title }}" class="img-fluid rounded">
                        </div>
                        <div class="post-content">
                            <h1 class="fs-3">{{ $post->title }}</h1>
                            <p class="text-muted">Chi tiết: {{ $post->detail }}</p>
                            <p class="text-muted">Mô tả: {{ $post->description }}</p>
                            <p class="text-muted">Đăng bởi {{ $post->author }} vào ngày {{ $post->created_at->format('d/m/Y') }}</p>
                            <div class="post-body">
                                {!! $post->content !!}
                            </div>
                        </div>
                    </div>
                    <div class="related-posts mt-5">
                        <h3 class="fs-4">Bài viết liên quan</h3>
                        <div class="row">
                            @foreach ($related_posts as $related_post)
                                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                    <div class="card">
                                        <img src="{{ asset('images/posts/' . $related_post->image) }}" class="card-img-top" alt="{{ $related_post->title }}">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $related_post->title }}</h5>
                                            <a href="{{ route('site.post.detail', ['slug' => $related_post->slug]) }}" class="btn btn-primary">Đọc tiếp</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
