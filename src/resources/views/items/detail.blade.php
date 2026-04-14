@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items/detail.css') }}">
@endsection

@section('content')
    <div class="item-detail__content">
        <div class="item-image__area">
            <div class="item-image__wrap">
                <img src="{{ asset('storage/' . $item->image_path) }}" alt="商品画像">
            </div>
        </div>

        <div class="item-detail__area">
            <h1 class="item-name">{{ $item->name }}</h1>
            <p class="item-brand">{{ $item->brand }}</p>
            <p class="item-price">{{ number_format($item->price) }}(税込)</p>
            <div class="item-icon">
                <form action="{{ $isLiked ? route('items.unlike', $item->id) : route('items.like', $item->id) }}"
                    method="POST">
                    @csrf
                    @if ($isLiked)
                        @method('DELETE')
                    @endif
                    <button class="item-icon__like-button">
                        <img class="item-icon__like-icon"
                            src="{{ $isLiked ? asset('images/ハートロゴ_ピンク.png') : asset('images/ハートロゴ_デフォルト.png') }}">
                        <span class="item-icon__like-count">{{ $likeCount }}</span>
                    </button>
                </form>

                <button class="item-icon__comment-button">
                    <img class="item-icon__comment-icon" src="{{ asset('images/ふきだしロゴ.png') }}">
                    <span class="item-icon__comment-count">{{ $commentCount }}</span>
                </button>
            </div>
            <a class="item-detail__purchase-btn btn" href="{{ route('items.buy', $item->id) }}">購入手続きへ</a>
            <div class="item-detail__group">
                <h2 class="item-detail__description-title">商品説明</h2>
                <div class="item-detail__description-content">{{ $item->description }}</div>
            </div>
            <div class="item-detail__group">
                <h2 class="item-detail__detail-title">商品の情報</h2>
                <div class="item-category__group
                 ">
                    <label class="item-category">カテゴリー</label>
                    <div class="item-category__content">
                        @foreach ($item->categories as $category)
                            <span class="category-tag">{{ $category->name }}</span>
                        @endforeach

                    </div>
                </div>
                <div class="item-condition__group">
                    <label class="item-condition">商品の状態</label>
                    <p class="item-condition__content">
                        {{ $conditions[$item->condition] }}</p>
                </div>
            </div>
            <div class="item-detail__group">
                <h2 class="item-detail__commnet-title">コメント（{{ $commentCount }}）</h2>
                <div class="item-detail__comments">
                    @foreach ($item->comments as $comment)
                        <div class="comment-item">
                            @if ($comment->user && $comment->user->image)
                                <img class="comment-user__icon"
                                    src="{{ asset('storage/user_images/' . $comment->user->image) }}">
                            @else
                                <div class="comment-user__icon--default"></div>
                            @endif
                            <p class="comment-user__name">{{ optional($comment->user)->name ?? '名無しユーザー' }}
                            </p>
                        </div>
                        <div class="comment-wrap">
                            <p class="comment-user__text">{{ $comment->comment }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
            <form class="comment-form" action="{{ route('items.comment', ['item_id' => $item->id]) }}" method="post">
                @csrf
                <label class="item-comment__form">商品へのコメント</label>

                <textarea class="item-comennt__form-content" name="comment">{{ old('comment') }}</textarea>
                @error('comment')
                    <p class="comment-form__error-message">{{ $message }} </p>
                @enderror
                <input class="comment-form__btn btn" type="submit" value="コメントを送信する">
            </form>
        </div>
    </div>
@endsection
