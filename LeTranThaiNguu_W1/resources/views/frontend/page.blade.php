@extends('layouts.site')
@section('title', 'Trang đơn')
@section('content')
  <style>
    .sidebar .nav-link {
      display: flex;
      align-items: center;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      color: #000;
      text-decoration: none;
      transition: all 0.3s ease;
    }
    .sidebar .nav-link.active {
      background-color: #f8f9fa;
      border-color: #dc3545;
      color: #dc3545;
    }
    .sidebar .nav-link:hover {
      background-color: #f8f9fa;
    }
    .sidebar .nav-link svg {
      margin-right: 10px;
    }
    .post-image {
      max-width: 100%;
      height: auto;
      border-radius: 5px;
      margin-bottom: 15px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .post-description {
      margin-bottom: 15px;
      color: #6c757d;
    }
    .accordion .card {
      border: none;
      margin-bottom: 15px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
    }
    .accordion .card-header {
      background-color: #f8f9fa;
      border: none;
    }
    .accordion .card-header h2 button {
      color: #343a40;
      font-size: 18px;
      font-weight: bold;
      text-decoration: none;
    }
    .accordion .card-header h2 button:hover {
      color: #dc3545;
    }
    .accordion .card-body {
      padding: 15px;
    }
  </style>
  <div class="container mt-4">
    <div class="row">
      <div class="col-md-3 sidebar">
        <nav class="nav flex-column">
          <a class="nav-link active" href="{{ route('contact.index') }}">
            <span class="mr-2">&#9742;</span> Liên hệ
          </a>
          <a class="nav-link" href="{{ route('site.page') }}">
            <span class="mr-2">&#128209;</span> Điều khoản và chính sách
          </a>
        </nav>
      </div>
      <div class="col-md-9">
        <h3>Điều Khoản & Chính Sách</h3>
        <div class="accordion" id="accordionExample">
          @foreach($posts as $post)
            <div class="card">
              <div class="card-header" id="heading{{ $post->id }}">
                <h2 class="mb-0">
                  <button class="btn btn-link {{ $loop->first ? '' : 'collapsed' }}" type="button" data-toggle="collapse" data-target="#collapse{{ $post->id }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="collapse{{ $post->id }}">
                    {{ $post->title }}
                  </button>
                </h2>
              </div>
              <div id="collapse{{ $post->id }}" class="collapse {{ $loop->first ? 'show' : '' }}" aria-labelledby="heading{{ $post->id }}" data-parent="#accordionExample">
                <div class="card-body">
                  @if($post->image)
                    <img src="{{ asset('images/posts/' . $post->image) }}" alt="{{ $post->title }}" class="post-image">
                  @endif
                  <p class="post-description">{{ $post->description }}</p>
                  {!! $post->detail !!}
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
@endsection
