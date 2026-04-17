@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items/sell.css') }}">
@endsection

@section('content')
    <div class="sell-container">
        <div class="sell-form">
            <h1 class="sell-form__heading content__heading">商品の出品</h1>

            <div class="sell-form__inner">
                <form class="sell-form__form" action="/sell" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="sell-form__group">
                        <label class="sell-form__label">商品画像</label>
                        <div class="item-image__box">
                            <input type="file" name="image_path" id="file-upload" accept="image/jpeg, image/png">
                            <label class="sell-form__upload" for="file-upload">画像を選択する
                            </label>
                        </div>
                        @error('image_path')
                            <p class="sell-form__error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    <h2 class="item-detail">商品の詳細
                    </h2>
                    <div class="sell-form__group">
                        <label class="sell-form__label" for="item-category">カテゴリー</label>
                        <div class="item-category__wrapper">
                            <div class="item-category__list">
                                @foreach ($categories as $category)
                                    <input class="item-category__checkbox" type="checkbox" name="category_id[]"
                                        id="category_{{ $category->id }}" value="{{ $category->id }}"
                                        {{ in_array($category->id, old('category_id', [])) ? 'checked' : '' }}>
                                    <label class="item-category__btn" for="category_{{ $category->id }}">
                                        {{ $category->name }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                        @error('category_id')
                            <p class="sell-form__error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="sell-form__group">
                        <label class="sell-form__label">商品の状態</label>
                        <details class="item-condition__select-inner">
                            <summary class="item-condition__placeholder">
                                <span class="item-condition__selected">選択してください</span>
                                <span class="item-condition__arrow">▼</span>
                            </summary>
                            <div class="item-condition__inner">
                                <label class="item-condition__option">
                                    <input type="radio" name="condition" value="1"
                                        {{ old('condition', $item->condition ?? '') == 1 ? 'checked' : '' }}>
                                    <span class="custom-radio">良好</span>
                                </label>
                                <label class="item-condition__option">
                                    <input type="radio" name="condition" value="2"
                                        {{ old('condition', $item->condition ?? '') == 2 ? 'checked' : '' }}>
                                    <span class="custom-radio">目立った傷や汚れなし</span>
                                </label>
                                <label class="item-condition__option">
                                    <input type="radio" name="condition" value="3"
                                        {{ old('condition', $item->condition ?? '') == 3 ? 'checked' : '' }}>
                                    <span class="custom-radio">やや傷や汚れあり</span>
                                </label>
                                <label class="item-condition__option">
                                    <input type="radio" name="condition" value="4"
                                        {{ old('condition', $item->condition ?? '') == 4 ? 'checked' : '' }}>
                                    <span class="custom-radio">状態が悪い</span>
                                </label>
                            </div>
                        </details>
                        @error('condition')
                            <p class="sell-form__error-message">{{ $message }} </p>
                        @enderror
                    </div>
                    <h2 class="item-description">商品名と説明</h2>
                    <div class="sell-form__group">
                        <label class="sell-form__label" for="item-name">商品名</label>
                        <input class="sell-form__input" type="text" name="name" id="item-name"
                            value="{{ old('name') }}">
                        @error('name')
                            <p class="sell-form__error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="sell-form__group">
                        <label class="sell-form__label" for="item-brand">ブランド名</label>
                        <input class="sell-form__input" type="text" name="brand" id="item-brand"
                            value="{{ old('brand') }}">
                    </div>
                    <div class="sell-form__group">
                        <label class="sell-form__label" for="item-content">商品説明</label>
                        <textarea class="sell-form__content" name="description" id="item-content">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="sell-form__error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="sell-form__group">
                        <label class="sell-form__label" for="item-price">販売価格</label>
                        <div class="price-input__wrap"> <span class="price-prefix">￥</span>
                            <input class="sell-form__input" type="number" name="price" id="item-price"
                                value="{{ old('price') }}">
                        </div>
                        @error('price')
                            <p class="sell-form__error-message">{{ $message }} </p>
                        @enderror
                    </div>
                    <input class="sell-form__btn btn" type="submit" value="出品する">
                </form>
            </div>
        </div>
    </div>
@endsection
