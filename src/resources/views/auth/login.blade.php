@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('content')
    <div class="login-form">
        <h1 class="login-form__heading content__heading">ログイン</h1>

        <div class="login-form__inner">
            <form class="login-form__form" action="/login" method="post" novalidate>
                @csrf
                <div class="login-form__group">
                    <label class="login-form__label" for="email">メールアドレス</label>
                    <input class="login-form__input" type="email" name="email" id="email"
                        value="{{ old('email') }}">
                    @error('email')
                        <p class="login-form__error-message"> {{ $message }}</p>
                    @enderror
                </div>
                <div class="login-form__group">
                    <label class="login-form__label" for="password">パスワード</label>
                    <input class="login-form__input" type="password" name="password" id="password">
                    @error('password')
                        <p class="login-form__error-message">{{ $message }}</p>
                    @enderror
                </div>
                <input class="login-form__btn btn" type="submit" value="ログインする">
            </form>
            <a class="login-form__link--register" href="/register">新規登録はこちら</a>
        </div>
    </div>
@endsection
