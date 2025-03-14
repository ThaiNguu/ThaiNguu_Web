<!-- site.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title')</title>
    <meta charset="UTF-8">
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/index.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;200;300;400;500;700;900&display=swap">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('fontawesome-free-6.5.2-web/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/card.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/css/footer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.7.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <style>
        
        a:hover {
        color: #f5a623; /* Orange color on hover */
        }
        .top-info {
            background-color: #f8f9fa;
            padding: 5px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .top-info .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
        }
        .top-info a {
            color: #333;
            text-decoration: none;
            margin: 0 10px;
        }
        .top-info a:hover {
            text-decoration: underline;
        }
        .header {
            padding: 10px 0;
            border-bottom: 1px solid #e0e0e0;
        }
        .header .logo img {
            width: 80px;
        }
        .header .navbar {
            flex-grow: 1;
        }
        .header .navbar-nav a {
            margin: 0 10px;
            color: #666;
            text-decoration: none;
            font-weight: bold;
        }
        .header .navbar-nav a.sale {
            color: #e74c3c;
        }
        .header .search-cart {
            display: flex;
            align-items: center;
        }
        .header .search-box {
            display: flex;
            align-items: center;
            margin-right: 20px;
        }
        .header .search-box input {
            border: 1px solid #ccc;
            padding: 5px 10px;
            border-radius: 5px 0 0 5px;
        }
        .header .search-box button {
            border: 1px solid #ccc;
            background-color: #f8f9fa;
            padding: 5px 10px;
            border-radius: 0 5px 5px 0;
        }
        .header .user-actions a {
            margin-left: 20px;
            color: #000;
            text-decoration: none;
        }
        .header .user-actions .cart {
            position: relative;
        }
        .header .user-actions .cart .badge {
            position: absolute;
            top: -5px;
            right: -10px;
            background-color: red;
            color: white;
            padding: 2px 5px;
            border-radius: 50%;
        }
        .card-title {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-size: 0.9rem; /* Adjust this value as needed */
        }
    </style>
</head>

<body>
    <x-main-menu/>
    @yield('content')
    <section class="footer">
        <x-footer-menu />
        <script>
            function openNav() {
                document.getElementById("mySidenav").style.width = "250px";
            }

            function closeNav() {
                document.getElementById("mySidenav").style.width = "0";
            }
        </script>
        @yield('footer')
    </section>
</body>

</html>
