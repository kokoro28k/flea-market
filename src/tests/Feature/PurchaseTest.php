<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Purchase;
use App\Models\Address;

class PurchaseTest extends TestCase
{
     use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
   public function test_user_can_purchase_item()
    {
        // ユーザーを作成する
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        // 出品者は別ユーザー
        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        // 住所を作成する
        $address = Address::factory()->create([
            'user_id' => $user->id,
        ]);

        // ログインして、購入する
        $this->actingAs($user)->withSession([
            'payment_method' => 'card',
            'address_id' => $address->id,
        ])->post(route('items.purchase',$item->id));

        // stripe決済
        $response = $this->actingAs($user)->withSession([
            'payment_method' => 'card',
            'address_id' => $address->id,
        ])->get(route('purchase.success',$item->id));

        // 購入後は商品一覧画面に遷移する
        $response->assertRedirect('/');

        // DBに購入履歴が保存される
        $this->assertDatabaseHas('purchases',[
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // 商品が購入済みになっている
        $this->assertDatabaseHas('items',[
            'id' => $item->id,
            'status' => 1,
        ]);
    }

    public function test_purchased_item_is_displayed_as_sold_on_index()
    {
        // ユーザーを作成する
        $user = User::factory()->create();

        // 出品者は別ユーザー
        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        // 住所を作成する
        $address = Address::factory()->create([
            'user_id' => $user->id,
        ]);


        // ログインする
        $this->actingAs($user);

        // 購入を実行
        $this->post(route('items.purchase',$item->id));

        // stripeで決済完了
        $response = $this->withSession([
            'payment_method' => 'card',
            'address_id' => $address->id,
        ])->get(route('purchase.success',$item->id));

        // DB の最新状態を反映
        $item->refresh();


        // 購入後、商品一覧画面に遷移する
        $response->assertRedirect('/');

        // 商品一覧画面を開く
        $response = $this->get(route('items.index'));

        $response->assertStatus(200);

        // 購入済み商品がSoldと表示される
        $response->assertSee($item->name);
        $response->assertSee($item->image_path);
        $response->assertSee('Sold'); 

        // DBに購入履歴が保存される
        $this->assertDatabaseHas('purchases',[
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // 商品が購入済みになっている
        $this->assertDatabaseHas('items',[
            'id' => $item->id,
            'status' => 1,
        ]);
    }

    public function test_user_purchased_item_is_displayed_on_mypage_buy()
    {
        // ユーザーを作成する
        $user = User::factory()->create();

        // 出品者は別ユーザー
        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        // 住所を作成する
        $address = Address::factory()->create([
            'user_id' => $user->id,
        ]);

        // ログインする
        $this->actingAs($user);

        $this->post(route('items.purchase',$item->id));

        // stripeで決済完了
        $response = $this->withSession([
            'payment_method' => 'card',
            'address_id' => $address->id,
        ])->get(route('purchase.success',$item->id));

        // DB の最新状態を反映
        $item->refresh();

        // 購入後、商品一覧画面に遷移する
        $response->assertRedirect('/');

        $item->refresh();

        // プロフィール画面を開く
        $response = $this->get(route('mypages.index',['page' => 'buy']));

        $response->assertStatus(200);

        // 購入した商品が表示される
        $response->assertSee($item->name);
        $response->assertSee($item->image_path);
       

        // DBに購入履歴が保存される
        $this->assertDatabaseHas('purchases',[
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // 商品が購入済みになっている
        $this->assertDatabaseHas('items',[
            'id' => $item->id,
            'status' => 1,
        ]);
    }

    public function test_selected_payment_method_is_displayed_on_subtotal_screen()
    {
        // ユーザーを作成する
        $user = User::factory()->create();

        // 出品者は別ユーザー
        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);
          // 初回ログイン時に設定した住所
        $address = Address::factory()->create([
            'user_id' => $user->id,
        ]);

        // ログインする
        $this->actingAs($user);
        // 購入画面を開く
        $response = $this->get(route('items.buy',$item->id));

        $response->assertStatus(200);

        // 支払い方法を選択する
        $response = $this->get(route('purchases.calculate',$item->id));

        $response->assertStatus(200);

        // 小計に反映される
        $response->assertSee(number_format($item->price));
        $response->assertSee('card');
    }

    public function test_changed_shipping_address_is_displayed_on_buy_page()
    {
        // ユーザーを作成する
        $user = User::factory()->create();

        // 出品者は別ユーザー
        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);
          // 初回ログイン時に設定した住所
        $address = Address::factory()->create([
            'user_id' => $user->id,
        ]);

        // ログインする
        $this->actingAs($user);

        // 送付先住所変更画面を開く
        $response = $this->get(route('purchases.address.edit',['item_id' => $item->id]));
        $response->assertStatus(200);

        $response = $this->put(route('purchases.address.update',['item_id' => $item->id]),[
            'postal_code' => '000-0001',
            'address' => '東京都新宿区テスト1-2',
            'building' => 'テストビル101',
        ]);
    
        // リダイレクト先が正しいか確認する
        $response->assertRedirect(route('items.buy', $item->id));

        // リダイレクト後、GETする
        $response = $this->get(route('items.buy',$item->id));
    

        // 登録した住所が反映される
        $response->assertSee('000-0001');
        $response->assertSee('東京都新宿区テスト1-2');
        $response->assertSee('テストビル101');

        $this->assertDatabaseHas('addresses',[
            'user_id' => $user->id,
            'postal_code' => '000-0001',
            'address' => '東京都新宿区テスト1-2',
            'building' => 'テストビル101',
        ]);
    }

    public function test_purchased_item_is_connected_to_shipping_address()
    {
        // ユーザーを作成する
        $user = User::factory()->create();

        // 出品者は別ユーザー
        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        // 初回ログイン時に設定した住所
        $address = Address::factory()->create([
            'user_id' => $user->id,
        ]);

        // ログインする
        $this->actingAs($user);

        // 送付先住所変更画面を開く
        $response =$this->get(route('purchases.address.edit',$item->id));
        $response->assertStatus(200);

        $response = $this->put(route('purchases.address.update',$item->id),[
            'postal_code' => '000-0001',
            'address' => '東京都新宿区テスト1-2',
            'building' => 'テストビル101',
        ]);
        $response->assertRedirect(route('items.buy',$item->id));

        // 更新後の住所
        $newAddress = Address::where('user_id',$user->id)->first();

        // 購入画面を開く
        $response = $this->get(route('items.buy',$item->id));
        $response->assertStatus(200);

        // 購入を実行
        $this->post(route('items.purchase',$item->id));

        // stripeで決済完了
        $response = $this->withSession([
            'payment_method' => 'card',
            'address_id' => $newAddress->id,
        ])->get(route('purchase.success',$item->id));

        $this->assertDatabaseHas('addresses',[
            'user_id' => $user->id,
            'postal_code' => '000-0001',
            'address' => '東京都新宿区テスト1-2',
            'building' => 'テストビル101',
        ]);

        // DBに購入履歴が保存される
        $this->assertDatabaseHas('purchases',[
            'user_id' => $user->id,
            'item_id' => $item->id,
            'address_id' => $newAddress->id,
        ]);
        
    }
}
