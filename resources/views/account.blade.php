@extends('layout')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/login_logout.css') }}">
@endsection

@section('title', '新規登録')

@section('main')
    <div id="loading" class="loading-overlay">
        <div class="loader"></div>
    </div>
    <div class="container">
        <div class="info">
            @if ($page_type == "login")
                <h1 class="login-title">FLATTOログインフォーム</h1>
                <h1 class="register-title" style="display: none">FLATTO登録フォーム</h1>
            @else
                <h1 class="login-title" style="display: none;">FLATTOログインフォーム</h1>
                <h1 class="register-title">FLATTO登録フォーム</h1>
            @endif
            <span>寄り道提案サービスです。会員登録をして新たな場所を開拓しましょう！</span>
        </div>
    </div>
    <div class="form">
        @if ($page_type == "login")
            <form class="register-form" style="display: none" action="{{ route('register') }}" method="post">
                @csrf
                <input type="email" placeholder="email address" name="email" required />
                <input type="password" placeholder="password" name="password" id="password" required
                       pattern="^(?:(?=.*[a-z])(?=.*[A-Z])|(?=.*[a-z])(?=.*[0-9])|(?=.*[a-z])(?=.*[!@#$%^&*])|(?=.*[A-Z])(?=.*[0-9])|(?=.*[A-Z])(?=.*[!@#$%^&*])|(?=.*[0-9])(?=.*[!@#$%^&*]))[a-zA-Z0-9!@#$%^&*]{6,}$"
                       title="6文字以上で、大文字または小文字の英字と数字をそれぞれ1つ以上含む必要があります。" />
                <input type="password" placeholder="confirmation" name="confirmation" id="confirmation" required />
                <button>create</button>
                <p class="message">登録が完了している場合は <a href="#">ログイン</a>をしてください</p>
            </form>
            <form class="login-form" action="{{ route('login') }}" method="post">
                @csrf
                <input type="email" placeholder="mail address" name="username" required />
                <input type="password" placeholder="password" name="password" required />
                <button>login</button>
                <p class="message">登録が完了していない場合は <a href="#">会員登録</a>をしてください</p>
            </form>
        @else
            <form class="register-form" action="{{ route('register') }}" method="post">
                @csrf
                <input type="email" placeholder="email address" name="email" required />
                <input type="password" placeholder="password" name="password" id="password" required
                       pattern="^(?:(?=.*[a-z])(?=.*[A-Z])|(?=.*[a-z])(?=.*[0-9])|(?=.*[a-z])(?=.*[!@#$%^&*])|(?=.*[A-Z])(?=.*[0-9])|(?=.*[A-Z])(?=.*[!@#$%^&*])|(?=.*[0-9])(?=.*[!@#$%^&*]))[a-zA-Z0-9!@#$%^&*]{6,}$"
                       title="6文字以上で、大文字または小文字の英字と数字をそれぞれ1つ以上含む必要があります。" />
                <input type="password" placeholder="confirmation" name="confirmation" id="confirmation" required />
                <button>create</button>
                <p class="message">登録が完了している場合は <a href="#">ログイン</a>をしてください</p>
            </form>
            <form class="login-form" style="display: none" action="{{ route('login') }}" method="post">
                @csrf
                <input type="email" placeholder="mail address" name="username" required />
                <input type="password" placeholder="password" name="password" required />
                <button>login</button>
                <p class="message">登録が完了していない場合は <a href="#">会員登録</a>をしてください</p>
            </form>
        @endif
    </div>
    @include('scripts.user-auth')
@endsection
