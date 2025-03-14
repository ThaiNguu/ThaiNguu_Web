<section id="post-new" class="p-3">
    <h2 class="text-warning text-center">
        <i class="fas fa-newspaper"></i> BÀI VIẾT
    </h2>
    <div class="row">
        @foreach ($post_new as $post)
        <div class="col-md-6">
            <div class="card h-100" id="card-news1">
                <img src="{{ asset('images/posts/'.$post->image) }}" alt="{{ $post->image }}" class="card-img-top">
                <div class="container">
                    <h4><a class="text-dark" href="{{ route('site.post.detail', ['slug' => $post->slug]) }}" title="{{ $post->title }}">{{ $post->title }}</a>
                    </h4>
                    <p>{{ $post->description }}</p>
                </div>
            </div>  
        </div>
        @endforeach
    </div>
</section>