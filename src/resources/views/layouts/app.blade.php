<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COACHTECH</title>
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    @yield('css')
</head>

<body>
    <div class="app">
        <header class="header">
            <div class="header-logo">
                <img src="{{ asset('images/COACHTECHヘッダーロゴ.png') }}" alt="COACHTECH">
            </div>

            {{-- ログイン・新規登録ページ以外は検索欄・ナビケーションバーを表示する --}}
            @unless (request()->routeIs('login') || request()->routeIs('register'))
                <form class="search-form" action="/" method="GET">
                    @csrf
                    <input class="search-form__item" type="text" name="keyword" placeholder="なにをお探しですか？"
                        value="{{ request('keyword') }}">
                </form>
                <nav class="header-nav">
                    @auth
                        <form action="/logout" method="post">
                            @csrf
                            <input class="header-nav__link" type="submit" value="ログアウト">
                        </form>
                    @endauth
                    @guest
                        <a class="header-nav__link" href="/login">ログイン</a>
                    @endguest
                    <a class="header-nav__link" href="/mypage">マイページ</a>
                    <a class="header-link__primary" href="/sell">出品</a>
                </nav>
            @endunless
        </header>
        <div class="content">
            @yield('content')
        </div>
    </div>
</body>

</html>
