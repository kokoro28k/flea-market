<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Like;
use App\Models\Category;
use App\Models\Comment;
use App\Models\CategoryItem;
use App\Models\Purchase;
use App\Models\Address;

class ItemTest extends TestCase
{
    use RefreshDatabase;
         /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_can_get_all_items()
    {
        $user = User::factory()->create();
    
        // 商品データを3件作成
        $items = Item::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        $response = $this->get('/');

        $response->assertStatus(200);
        
        // 作成した全商品の名前と画像が表示されていることを確認
        foreach($items as $item){
            $response->assertSee($item->name);
            $response->assertSee($item->image_path);
        }
    }

    public function test_item_shows_sold()
    {
        $user = User::factory()->create();

        // 購入済み商品を一件作成
        $item = Item::factory()->create([
            'status'=>1,
            'user_id' => $user->id,
        ]);

        $response = $this->get('/');

        // 正常に表示される
        $response->assertStatus(200);
        
        // 購入済み商品がSoldと表示される
        $response->assertSee($item->name);
        $response->assertSee($item->image_path);
        $response->assertSee('Sold');
    }    

    
    public function test_my_items_are_not_displayed_in_item_list()
    {
        // 出品するユーザー、そのユーザーによって出品された商品を作成
        $user = User::factory()->create();

        // ログインする
        $this->actingAs($user);
        $this->assertAuthenticatedAs($user);


        $item = Item::factory()->create(['user_id'=>$user->id]);

        $response = $this->get('/?tab=recommend');

        // 正常に表示される
        $response->assertStatus(200);
        
        // ユーザーが出品した商品は表示されない
        $response->assertDontSee($item->name);
        $response->assertDontSee($item->image_path);
    }    

    public function test_liked_items_are_displayed_in_my_list()
    {
        // ユーザーを作成する
        $user = User::factory()->create();

        // いいねした商品を作成する
        $likedItem = Item::factory()->create([
            'user_id' => $user->id,
        ]);
        
        // Likeレコードを作成する
        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $likedItem->id,
        ]);

        // ログインする
        $this->actingAs($user);
        $this->assertAuthenticatedAs($user);

        $response = $this->get(route('items.index',['tab' => 'mylist']));

        // 正常に表示される
        $response->assertStatus(200);
        
        // ユーザーがいいねした商品が表示される
        $response->assertSee($likedItem->name);
        $response->assertSee($likedItem->image_path);
    }  
    
     public function test_liked_and_purchased_item_shows_sold_in_my_list()
    {
        
        // ユーザーを作成する
        $user = User::factory()->create();

        // 購入済み商品を作成する
        $item = Item::factory()->create([
            'status'=> 1,
            'user_id' => $user->id,
        ]);
  
        // マイリストはいいねがついた商品が表示される
        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // Address を作成
        $address = Address::factory()->create([
            'user_id' => $user->id,
        ]);

        // Purchaseレコードを作成する
        Purchase::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'address_id' => $address->id,
        ]);

        // ログインする
        $this->actingAs($user);
        $this->assertAuthenticatedAs($user);

        $response = $this->get(route('items.index',['tab' => 'mylist']));

        // 正常に表示される
        $response->assertStatus(200);
        
        // 購入済み商品がSoldと表示される
        $response->assertSee($item->name);
        $response->assertSee($item->image_path);
        $response->assertSee('Sold');
    }   
    
    public function test_guest_cannot_see_my_list_items()
    {  
        
        // ユーザーを作成する
        $user = User::factory()->create();
    
        $item = Item::factory()->create([
            'user_id' => $user->id,
        ]);

        $response = $this->get(route('items.index',['tab' => 'mylist']));

        // 正常に表示される
        $response->assertStatus(200);
        
        $response->assertDontSee($item->name);
        $response->assertDontSee($item->image_path);

        // 認証されていない
        $this->assertGuest();
    }

   public function test_item_matching_keyword_are_displayed()
    {  
        // ユーザーを作成する
        $user = User::factory()->create();

        // 商品データを作成する
        $item1 = Item::factory()->create([
            'name' => '革靴',
            'user_id' => $user->id,
        ]);
        $item2 = Item::factory()->create([
            'name' => 'ペンケース',
            'user_id' => $user->id,
        ]);
        $item3 = Item::factory()->create([
            'name' => '食器セット',
            'user_id' => $user->id,
        ]);

        // 検索する
        $response = $this->get('/?keyword=革');

        // 正常に表示される
        $response->assertStatus(200);
        
        $response->assertSee('革靴');
        $response->assertDontSee('ペンケース');
        $response->assertDontSee('食器セット');

        // 未認証でも検索できる
        $this->assertGuest();
    } 

    public function test_items_matching_keyword_are_displayed_in_my_list()
    {  
        // ユーザーを作成する
        $user = User::factory()->create();

        // 商品データを作成する
        $item1 = Item::factory()->create([
            'name' => '革靴',
            'user_id' => $user->id,
        ]);
        $item2 = Item::factory()->create([
            'name' => 'ペンケース',
            'user_id' => $user->id,
        ]);
        $item3 = Item::factory()->create([
            'name' => '食器セット',
            'user_id' => $user->id,
        ]);

        // マイリストはいいねがついた商品が表示される
        Like::factory()->create([
            'user_id' => $user->id,
            'item_id' => $item1->id,
        ]);

        // ログインする
        $this->actingAs($user);
        $this->assertAuthenticatedAs($user);

        // 検索してマイリストタブを開く
        $response = $this->get(route('items.index',['tab' => 'mylist',
        'keyword' => '革',]));

        // 正常に表示される
        $response->assertStatus(200);
        
        $response->assertSee('革靴');
        $response->assertDontSee('ペンケース');
        $response->assertDontSee('食器セット');
    } 

    public function test_item_detail_is_displayed_with_brand()
    {
        // ユーザーを作成する
        $user = User::factory()->create();

        // ブランドありの商品データを作成する
        $category = Category::factory()->create();
        $item = Item::factory()->create([
            'name' => '腕時計',
            'brand' => 'Rolax',
            'user_id' => $user->id,
        ]);

        CategoryItem::factory()->create([
            'item_id' => $item->id,
            'category_id' => $category->id,
        ]);

        // いいねを3件作成する
        Like::factory()->count(3)->create([
            'item_id'=> $item->id,
            'user_id' => $user->id,
        ]);

        // コメントを2件作成する
        $comments = Comment::factory()->count(2)->create([
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);
       
        // Blade に渡される値をテスト側でも計算する
        $likeCount = $item->likes->count();
        $commentCount = $item->comments->count();

        $response = $this->get(route('items.show',['item_id' => $item->id]));

        // 正常に表示される
        $response->assertStatus(200);
        
        $response->assertSee($item->image_path);
        $response->assertSee($item->name);
        $response->assertSee($item->brand);
        $response->assertSee(number_format($item->price));
        $response->assertSee($likeCount);
        $response->assertSee($commentCount);
        $response->assertSee($item->description);
        $response->assertSee($category->name);
        $response->assertSee($item->condition);
        $response->assertSee($comments[0]->user->image);
        $response->assertSee($comments[0]->comment);
    } 

 public function test_item_detail_is_displayed_when_brand_null()
    {
        // ユーザーを作成する
        $user = User::factory()->create();
        
        // ブランドなし商品データを作成する
        $category = Category::factory()->create();
        $item = Item::factory()->create([
            'name' => 'ノートPC',
            'brand' => null,
            'user_id' => $user->id,
        ]);

        CategoryItem::factory()->create([
            'item_id' => $item->id,
            'category_id' => $category->id,
        ]);

        // いいねを3件作成する
        Like::factory()->count(3)->create([
            'item_id'=> $item->id,
            'user_id' => $user->id,
        ]);

        // コメントを2件作成する
        $comments = Comment::factory()->count(2)->create([
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);
       
        // Blade に渡される値をテスト側でも計算する
        $likeCount = $item->likes->count();
        $commentCount = $item->comments->count();

        $response = $this->get(route('items.show',['item_id' => $item->id]));

        // 正常に表示される
        $response->assertStatus(200);
        
        $response->assertSee($item->image_path);
        $response->assertSee($item->name);
        $response->assertSee(number_format($item->price));
        $response->assertSee($likeCount);
        $response->assertSee($commentCount);
        $response->assertSee($item->description);
        $response->assertSee($category->name);
        $response->assertSee($item->condition);
        $response->assertSee($comments[0]->user->image);
        $response->assertSee($comments[0]->comment);

        $response->assertDontSee('Rolax');
    } 


    public function test_item_categories_are_displayed()
    {
        // ユーザーを作成する
        $user = User::factory()->create();

        // カテゴリを複数作成する
        $categories = Category::factory()->count(2)->create();
        $item = Item::factory()->create([
            'user_id' => $user->id,
        ]);

        // 中間テーブルと紐づける
        foreach ($categories as $category) {
            CategoryItem::factory()->create([
                'item_id' => $item->id,
                'category_id' => $category->id,
            ]);
        }

        $response = $this->get(route('items.show',['item_id' => $item->id]));

        // 正常に表示される
        $response->assertStatus(200);
      
        $response->assertSee($categories[0]->name);
        $response->assertSee($categories[1]->name);
    } 
}
