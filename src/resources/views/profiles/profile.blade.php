@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profiles/profile.css') }}">
@endsection

@section('content')
    <div class="list-content">
        <div class="profile-header">
            <div class="profile-info">
                <div class="profile-image" style="background-image: url('{{ asset('storage/' . $user?->image) }}');">
                </div>
                <div class="user-name">{{ $user?->name ?? '' }}</div>
            </div>
            <a class="profile-edit__link" href="{{ route('profiles.edit') }}">プロフィールを編集</a>
        </div>

        <div class="list-content__inner">
            <nav class="subnav">
                <a class="subnav__link {{ $page === 'sell' ? 'active' : '' }}" href="/mypage?page=sell">出品した商品</a>
                <a class="subnav__link {{ $page === 'buy' ? 'active' : '' }}" href="/mypage?page=buy">購入した商品</a>
            </nav>
            <hr class="full-width-line">
        </div>
        <div class="items-list__container">
            <div class="items-list">
                @if ($page === 'sell')
                    @foreach ($items as $item)
                        <div class="item-card">
                            <div class="item-card__image-wrap">
                                <img src="{{ asset('storage/' . $item->image_path) }}" alt="商品画像">
                                <div class="item-card__overlay">
                                </div>
                            </div>
                            <div class="item-card__name">{{ $item->name }}</div>
                        </div>
                    @endforeach
                @endif
                @if ($page === 'buy')
                    @foreach ($purchases as $purchase)
                        <div class="item-card">
                            <div class="item-card__image-wrap">
                                <img src="{{ asset('storage/' . $purchase->item->image_path) }}" alt="商品画像">
                            </div>
                            <div class="item-card__name">{{ $purchase->item->name }}</div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    @endsection
