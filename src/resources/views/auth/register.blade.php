@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')
    <div class="register-form">
        <h1 class="register-form__heading content__heading">会員登録</h1>

        <div class="register-form__inner">
            <form class="register-form__form" action="/register" method="post" novalidate>
                @csrf
                <div class="register-form__group">
                    <label class="register-form__label" for="name">ユーザー名</label>
                    <input class="register-form__input" type="text" name="name" id="name"
                        value="{{ old('name') }}">
                    @error('name')
                        <p class="register-form__error-message">{{ $message }}</p>
                    @enderror
                </div>
                <div class="register-form__group">
                    <label class="register-form__label" for="email">メールアドレス</label>
                    <input class="register-form__input" type="email" name="email" id="email"
                        value="{{ old('email') }}">
                    @error('email')
                        <p class="register-form__error-message">{{ $message }}</p>
                    @enderror
                </div>
                <div class="register-form__group">
                    <label class="register-form__label" for="password">パスワード</label>
                    <input class="register-form__input" type="password" name="password" id="password">
                </div>
                <div class="register-form__group">
                    <label class="register-form__label" for="password">確認用パスワード</label>
                    <input class="register-form__input" type="password" name="password_confirmation"
                        id="password_confirmation">
                    @error('password')
                        <p class="register-form__error-message">{{ $message }}</p>
                    @enderror
                </div>
                <input class="register-form__btn btn" type="submit" value="登録する">
            </form>
            <a class="register-form__link--login" href="/login">ログインはこちら</a>
        </div>
    </div>
@endsection
