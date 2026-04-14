<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;
use App\Models\Address;

class ProfileTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_mypage_displays_user_profile_and_items()
    {
        // ユーザーを作成する
        $user = User::factory()->create();
  
        // ユーザーが出品した商品
        $sellItem = Item::factory()->create([
            'user_id' => $user->id,
        ]);

        // 購入した商品（出品者は別ユーザー）
        $seller = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'status' => 1,
        ]);

        $address = Address::factory()->create([
            'user_id' => $user->id,
        ]);

        // 購入履歴を作成
        Purchase::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'address_id' => $address->id,
            'payment_method' => 'card',
        ]);

        // ログインする
        $this->actingAs($user);

        // プロフィール画面を開く
        $response = $this->get('/mypage');
        $response->assertStatus(200);

        // ユーザー名とプロフィール画像が表示される
        $response->assertSee($user->name);
        $response->assertSee($user->image);

        // 出品した商品一覧を開く
        $response = $this->get(route('mypages.index',['page' => 'sell']));
        $response->assertStatus(200);

        // 出品した商品が表示される
        $response->assertSee($sellItem->name);
        $response->assertSee($sellItem->image_path);
        
        // 購入した商品一覧を開く
        $response = $this->get(route('mypages.index',['page' => 'buy']));
        $response->assertStatus(200);

        // 購入した商品が表示される
        $response->assertSee($item->name);
        $response->assertSee($item->image_path);
    }

    public function test_user_profile_is_displayed_on_profile_edit()
    {
        // ユーザーを作成する
        $user = User::factory()->create([
            'profile_completed' => 1,
        ]);

        $address = Address::factory()->create([
            'user_id' => $user->id,
            'postal_code' => '000-0001',
            'address' => '東京都新宿区テスト1-2',
            'building' => 'テストビル101',
        ]);
        // ログインする
        $this->actingAs($user);

        // プロフィール画面を開く
        $response = $this->get(route('profiles.edit',$user->id,$address->id));
        $response->assertStatus(200);

        $response->assertSee($user->name);
        $response->assertSee($user->image);
        $response->assertSee($address->postal_code);
        $response->assertSee($address->address);
        $response->assertSee($address->building);
    }



}
