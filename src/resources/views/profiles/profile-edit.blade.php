@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profiles/profile-edit.css') }}">
@endsection

@section('content')
    <div class="profile-form">
        <h1 class="profile-form__heading content__heading">プロフィール設定</h1>

        <div class="profile-form__inner">
            <form class="profile-form__form" action="{{ route('profiles.update') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="profile-form__header">
                    <div class="profile-form__image"
                        style="background-image: url('{{ asset('storage/' . $user->image) }}');">
                    </div>
                    <label class="profile-form__upload" for="file-upload">画像を選択する
                    </label>
                    <input id="file-upload" type="file" name="image" accept="image/jpeg, image/png,">
                </div>

                <div class="profile-form__group">
                    <label class="profile-form__label" for="name">ユーザー名</label>
                    <input class="profile-form__input" type="text" name="name"
                        id="name"value="{{ $user->name ?? '' }}">
                    @error('name')
                        <p class="profile-form__error-message">{{ $message }} </p>
                    @enderror
                </div>

                <div class="profile-form__group">
                    <label class="profile-form__label" for="postal_code">郵便番号</label>
                    <input class="profile-form__input" type="text" name="postal_code" id="postal_code"
                        value="{{ old('postal_code', $address->postal_code ?? '') }}">
                    @error('postal_code')
                        <p class="profile-form__error-message">{{ $message }}</p>
                    @enderror
                </div>

                <div class="profile-form__group">
                    <label class="profile-form__label" for="address">住所</label>
                    <input class="profile-form__input" type="text" name="address" id="address"
                        value="{{ old('address', $address->address ?? '') }}">
                    @error('address')
                        <p class="profile-form__error-message"> {{ $message }}</p>
                    @enderror
                </div>
                <div class="profile-form__group">
                    <label class="profile-form__label" for="building">建物名</label>
                    <input class="profile-form__input" type="text" name="building" id="building"
                        value="{{ old('building', $address->building ?? '') }}">
                </div>
                <input class="profile-form__btn btn" type="submit" value="更新する">
            </form>
        </div>
    </div>
@endsection
