<div class="card h-100">
    <img src="{{ asset('images/posts/'.$postitem->image) }}" class="card-img-top" alt="{{ $postitem->title }}">
    <div class="card-body">
        <h5 class="card-title">{{ $postitem->title }}</h5>
        <p class="card-text">{{ Str::limit($postitem->excerpt, 100) }}</p>
        <a href="{{ route('site.post.detail', ['slug' => $postitem->slug]) }}" class="btn btn-primary">Chi tiết bài viết</a>
    </div>
</div>
