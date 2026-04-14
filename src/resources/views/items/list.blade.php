@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/items/list.css') }}">
@endsection

@section('content')
    <div class="list-content">
        <nav class="subnav">
            <a class="subnav__link {{ $tab === 'recommend' ? 'active' : '' }}"
                href="{{ request('keyword') ? route('items.index', ['keyword' => request('keyword')]) : route('items.index') }}">おすすめ</a>
            <a class="subnav__link {{ $tab === 'mylist' ? 'active' : '' }}"
                href="{{ route('items.index', ['tab' => 'mylist', 'keyword' => request('keyword')]) }}">マイリスト</a>
        </nav>

        <hr class="full-width-line">
        <div class="items-list__container">
            <div class="items-list">
                @foreach ($items as $item)
                    <div class="item-card">
                        <div class="item-card__image-wrap">
                            <a class="item-link" href="{{ route('items.show', $item->id) }}">
                                <img src="{{ asset('storage/' . $item->image_path) }}" alt="商品画像">
                            </a>
                            <div class="item-card__overlay">
                                @if ($item->status == 1)
                                    <div class="item-card__status">Sold</div>
                                @endif
                            </div>
                        </div>
                        <div class="item-card__name">{{ $item->name }}</div>
                    </div>
                @endforeach
            </div>
        </div>
    @endsection
