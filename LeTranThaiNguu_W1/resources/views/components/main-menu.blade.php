<style>
  a:hover {
    color: #f5a623; /* Orange color on hover */
  }
  .top-info {
    position: fixed;
    width: 100%;
    top: 0;
    left: 0;
    z-index: 1000; /* Ensure the top-info is above other elements */
    background-color: #333; /* Dark background */
    color: #fff; /* White text */
    padding: 10px 0; /* Padding for better appearance */
    font-family: 'Arial', sans-serif; /* Modern font */
  }
  .top-info a {
    color: #fff; /* White color for links */
    margin: 0 20px; /* Margin between links */
    font-size: 16px; /* Larger font size */
    text-transform: uppercase; /* Uppercase text */
    display: flex; /* Flexbox for aligning icon and text */
    align-items: center; /* Center align icon and text */
    transition: color 0.3s; /* Smooth color transition */
  }
  .top-info a i {
    margin-right: 8px; /* Margin between icon and text */
    font-size: 20px; /* Larger icons */
  }
  .top-info a:hover {
    color: #f5a623; /* Orange color on hover */
  }
  .header {
    position: fixed;
    width: 100%;
    top: 50px; /* Adjust based on top-info height to prevent overlap */
    left: 0;
    z-index: 999; /* Ensure the header is below top-info */
    background-color: #fff; /* White background */
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Subtle shadow */
    font-family: 'Arial', sans-serif; /* Modern font */
  }
  .navbar-collapse {
    background-color: #fff; /* White background for navbar */
  }
  .navbar-nav .nav-link {
    color: #000; /* Ensure nav links are visible */
    font-size: 16px; /* Consistent font size */
    padding: 10px 15px; /* Padding for better spacing */
    text-transform: uppercase; /* Uppercase text */
    font-weight: bold; /* Bold text */
    transition: color 0.3s, background-color 0.3s; /* Smooth color transition */
  }
  .navbar-nav .nav-link:hover {
    color: #f5a623; /* Orange color on hover */
    background-color: #f1f1f1; /* Light grey background on hover */
    border-radius: 5px; /* Rounded corners on hover */
  }
  .search-box input[type="text"] {
    border: 1px solid #ccc;
    border-radius: 3px;
    padding: 5px;
  }
  .search-box button {
    border: none;
    background: none;
  }
  .search-box button i {
    color: #000;
  }
  .user-actions a {
    color: #000;
    font-size: 16px; /* Consistent font size */
    margin-left: 10px; /* Margin for spacing */
    text-transform: uppercase; /* Uppercase text */
    font-weight: bold; /* Bold text */
    transition: color 0.3s; /* Smooth color transition */
  }
  .user-actions a:hover {
    color: #f5a623;
  }
  /* Add padding to the top of the body to prevent content overlap */
  body {
    padding-top: 110px; /* Adjust based on total height of fixed elements */
  }
</style>

<section class="top-info">
  <div class="container d-flex justify-content-center align-items-center">
    <div class="contact-info d-flex flex-wrap justify-content-center">
      <a href="#" class="d-flex align-items-center"><i class="fa fa-map-marker" aria-hidden="true"></i> 79 Stores Nationwide</a>
      <a href="{{ route('contact.index') }}" class="text-danger d-flex align-items-center"><i class="fa fa-phone" aria-hidden="true"></i> LIÊN HỆ</a>
      <a href="{{ route('contact.index') }}" class="d-flex align-items-center"><i class="fa fa-truck" aria-hidden="true"></i> Điều khoản</a>
      <a href="#" class="d-flex align-items-center"><i class="fa fa-headphones" aria-hidden="true"></i> Help Center</a>
      <a href="#" class="d-flex align-items-center"><i class="fa fa-search" aria-hidden="true"></i> Careers</a>
    </div>
  </div>
</section>

<header class="header" style="margin-top: -5px">
  <div class="container-fluid d-flex justify-content-between align-items-center">
    <div class="logo me-3">
      <a href="{{ asset('/') }}">
        <img src="{{ asset('images/logo1.png') }}" alt="Logo">
      </a>
    </div>
    <div class="d-flex justify-content-between align-items-center flex-grow-1">
      <nav class="navbar navbar-expand-lg navbar-light flex-grow-1">
        <div class="container-fluid">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
            <i class="fa-solid fa-bars text-dark fs-3"></i>
          </button>
          <div class="collapse navbar-collapse" id="navbarNavDropdown">
            <ul class="navbar-nav">
              @foreach ($list_menu as $row_menu)
                <x-main-menu-item :rowmenu="$row_menu"/>
              @endforeach
            </ul>
          </div>
        </div>
      </nav>
      <div class="d-flex align-items-center">
        <div class="search-box me-3">
          <form method="get" action="{{ route('search') }}" name="search">
            <input name="query" type="text" placeholder="Tìm kiếm...">
            <button type="submit"><i class="fa fa-search" aria-hidden="true"></i></button>
          </form>
        </div>
        <div class="user-actions">
          @php
            $carts = session('carts', []);
            $countqty = (is_array($carts))?count($carts):0;
          @endphp
          <a href="{{ route('site.cart.index') }}" class="cart me-3">
            <i class="fa fa-shopping-cart" aria-hidden="true"></i>
            <span class="badge" id="showqty">{{ $countqty }}</span>
          </a>

          @if (Auth::check())
            @php
              $user = Auth::user();
            @endphp
            {{ $user->name; }}<a href="{{ route('website.logout') }}">ĐĂNG XUẤT</a>
          @else
            <a href="{{ route('website.getlogin') }}"><i class="fa fa-user" aria-hidden="true"></i> ĐĂNG NHẬP</a><a href="#">ĐĂNG KÝ</a>
          @endif
        </div>
      </div>
    </div>
  </div>
</header>
