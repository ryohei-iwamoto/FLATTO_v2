<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="content-language" content="ja">
    <title>FLATTO</title>
    <link rel="icon" type="image/png" href="{{ asset('img/favcon.png') }}" sizes="32x32">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- <link rel="stylesheet" href="{{ asset('css/style_index.css') }}"> -->
    <link rel="stylesheet" href="{{ asset('css/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/apology.css') }}">
    <link rel="stylesheet" href="{{ asset('css/decoration.css') }}">
    <link rel="stylesheet" href="{{ asset('css/near_place.css') }}">
    <link rel="stylesheet" href="{{ asset('css/via_style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/rate.css') }}">
    <link rel="stylesheet" href="{{ asset('css/detail_btn.css') }}">
    <link rel="stylesheet" href="{{ asset('css/checkbox.css') }}">
    <link rel="stylesheet" href="{{ asset('css/review.css') }}">
    <link rel="stylesheet" href="{{ asset('css/btn.css') }}">
    <link rel="stylesheet" href="{{ asset('css/img.css') }}">
    <link rel="stylesheet" href="{{ asset('css/scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select_box.css') }}">
    <link rel="stylesheet" href="{{ asset('css/search_btn.css') }}">
    @yield('head')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
        crossorigin="anonymous"></script>
</head>

<body class="body">
    <nav class="navbar navbar-expand-md navbar-dark bg-danger">
        @auth('web')
        <a class="navbar-brand" href="/"><img src="{{ asset('img/map_logo.png') }}"></a>
        <div class="navbar-collapse collapse justify-content-stretch" id="navbar">
            <ul class="navbar-nav ms-auto mt-2">
                <li class="nav-item"><a class="nav-link" href="/mypage">マイページ</a></li>
                <li class="nav-item"><a class="nav-link" href="/logout">ログアウト</a></li>
            </ul>
        </div>
        @endauth
        @auth('tenant')
        <a class="navbar-brand" href="/"><img src="{{ asset('img/map_logo.png') }}"></a>
        <div class="navbar-collapse collapse justify-content-stretch" id="navbar">
            <ul class="navbar-nav ms-auto mt-2">
                <li class="nav-item"><a class="nav-link" href="/tenanthome">店舗登録</a></li>
                <li class="nav-item"><a class="nav-link" href="/keyword">キーワード</a></li>
                <li class="nav-item"><a class="nav-link" href="/logout">ログアウト</a></li>
            </ul>
        </div>
        @endauth
        @guest
        <a class="navbar-brand" href="/"><img src="{{ asset('img/map_logo.png') }}"></a>
        <div class="navbar-collapse collapse justify-content-stretch" id="navbar">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="/register">新規登録</a></li>
                <li class="nav-item"><a class="nav-link" href="/login">ログイン</a></li>
            </ul>
        </div>
        @endguest
    </nav>
    @if (session()->has('message'))
    <header>
        <div class="alert alert-light mb-0 text-center" role="alert">
            {{ session('message') }}
        </div>
    </header>
    @endif

    <main>
        @yield('main')
    </main>

    <!-- <footer class="mb-5 small text-center text-muted" id="footer">
            &copy; 2022 S04
        </footer> -->
</body>

</html>
