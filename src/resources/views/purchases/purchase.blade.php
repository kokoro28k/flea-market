@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/purchases/purchase.css') }}">
@endsection

@section('content')
    <div class="purchase-container">
        <div class="purchase-inner">
            <div class="purchase-content">
                <div class="item-info">
                    <div class="item-info__inner">
                        <div class="item-card__image-wrap">
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="商品画像">
                        </div>
                        <div class="item-info__content">
                            <p class="item-name">{{ $item->name }}</p>
                            <p class="item-price">￥{{ number_format($item->price) }}</p>
                        </div>
                    </div>
                </div>
                <div class="payment-info">
                    <div class="payment-info__inner">
                        <p class="payment-method">
                            支払い方法
                        </p>
                        <form action="{{ route('purchases.calculate', ['item_id' => $item->id]) }}" method="get">

                            <details class="payment-method__select-inner">
                                <summary class="payment-method__placeholder">
                                    <span class="payment-method__selected">選択してください</span>
                                    <span class="payment-method__arrow">▼</span>
                                </summary>
                                <div class="payment-method__inner">
                                    <label class="payment-method__option">
                                        <input type="radio" name="payment_method" value="konbini"
                                            {{ $payment_method == 'konbini' ? 'checked' : '' }}
                                            onchange="this.closest('form').submit()">
                                        <span class="custom-radio">コンビニ払い</span>
                                    </label>
                                    <label class="payment-method__option">
                                        <input type="radio" name="payment_method" value="card"
                                            {{ $payment_method == 'card' ? 'checked' : '' }}
                                            onchange="this.closest('form').submit()">
                                        <span class="custom-radio">カード払い</span>
                                    </label>
                                </div>
                            </details>
                        </form>
                        @error('payment_method')
                            <p class="purchase-form__error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="shipping-info">
                    <div class="shipping-info__inner">
                        <div class="shipping-info__header">
                            <p class="shipping-address">配送先</p>
                            <a href="{{ route('purchases.address.edit', ['item_id' => $item->id]) }}"
                                class="shipping-address__edit-btn">変更する</a>
                        </div>
                        <div class="shipping-address__body">
                            <p class="shipping-address__postal_code">〒{{ $user->address->postal_code }}</p>
                            <p class="shipping-address__address">{{ $user->address->address }}</p>
                            <p class="shipping-address__buinding">{{ $user->address->building }}</p>
                            <input type="hidden" name="address_id" value="default">
                        </div>
                        @error('address_id')
                            <p class="purchase-form__error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="purchase-summary">
                <table class="purchase-detail">
                    <tr class="purchase-detail__inner">
                        <th class="purchase-detail__label">商品代金</th>
                        <td class="purchase-detail__data">￥{{ number_format($item->price) }}</td>
                    </tr>
                    <tr class="purchase-detail__inner">
                        <th class="purchase-detail__label">支払い方法</th>
                        <td class="purchase-detail__data">
                            @if ($payment_method == 'konbini')
                                コンビニ払い
                            @elseif ($payment_method == 'card')
                                クレジットカード
                            @endif
                        </td>
                    </tr>
                </table>
                <form action="{{ route('items.purchase', ['item_id' => $item->id]) }}" method="post">
                    @csrf
                    <input type="hidden" name="payment_method" value="{{ $payment_method }}">
                    <input type="hidden" name="address_id" value="{{ $address_id }}">
                    <input class="purchase-form__btn btn" type="submit" value="購入する">
                </form>
            </div>
        </div>
    </div>
@endsection
