@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/profiles/address.css') }}">
@endsection

@section('content')
    <div class="address-form">
        <h1 class="address-form_heading content__heading">住所の変更</h1>

        <div class="address-form__inner">
            <form class="address-form__form" action="{{ route('purchases.address.update', ['item_id' => $item_id]) }}"
                method="post">
                @method('PUT')
                @csrf
                <div class="address-form__group">
                    <label class="address-form__label" for="postal_code">郵便番号</label>
                    <input class="address-form__input" type="text" name="postal_code" id="postal_code"
                        value="{{ old('postal_code') }}">
                    @error('postal_code')
                        <p class="address-form__error-message">
                            {{ $message }}</p>
                    @enderror
                </div>
                <div class="address-form__group">
                    <label class="address-form__label" for="address">住所</label>
                    <input class="address-form__input" type="text" name="address" id="address"
                        value="{{ old('address') }}">
                    @error('address')
                        <p class="address-form__error-message">
                            {{ $message }}</p>
                    @enderror
                </div>
                <div class="address-form__group">
                    <label class="address-form__label" for="building">建物名</label>
                    <input class="address-form__input" type="text" name="building" id="building"
                        value="{{ old('building') }}">
                </div>
                <input class="address-form__btn btn" type="submit" value="更新する">
            </form>
        </div>
    </div>
@endsection
