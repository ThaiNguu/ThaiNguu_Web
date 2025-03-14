@extends('layouts.site')
@section('title', 'Bài viết')
@section('content')
    <section class="hdl-maincontent py-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="accordion mb-4" id="topicAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="topicHeading">
                                <button class="accordion-button collapsed bg-warning text-light" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTopic" aria-expanded="true" aria-controls="collapseTopic">
                                    DANH MỤC CHỦ ĐỀ
                                </button>
                            </h2>
                            <div id="collapseTopic" class="accordion-collapse collapse show" aria-labelledby="topicHeading" data-bs-parent="#topicAccordion">
                                <div class="accordion-body">
                                    <ul class="list-group list-group-flush">
                                        @foreach($topics as $topicItem)
                                            <li class="list-group-item">
                                                <a href="{{ route('site.post.topic', ['slug' => $topicItem->slug]) }}">{{ $topicItem->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="bg-info p-3 mb-4 rounded">
                        <h3 class="fs-5 py-3 text-center text-light">{{ $topic->name ?? 'TẤT CẢ BÀI VIẾT' }}</h3>
                    </div>
                    <div class="post-category mt-4">
                        <div class="row">
                            @foreach ($list_post as $postitem)
                                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                                    <x-post-card :postitem="$postitem" />
                                </div>
                            @endforeach
                        </div>
                        <div class="row mt-4">
                            <div class="col-12 d-flex justify-content-center">
                                {{ $list_post->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
