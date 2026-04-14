<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Like;

class LikeTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_like_other_users_item()
    {
        $user = User::factory()->create();

        // 出品者は別ユーザー
        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        // ログインする
        $this->actingAs($user);

        $response = $this->post(route('items.like',$item->id));

        // DBにLikeが保存される
        $this->assertDatabaseHas('likes',[
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        // いいね数が増加する
        $this->assertEquals(1,$item->refresh()->likes()->count());
    }

    public function test_liked_icon_changes_after_liking()
    {
        $user = User::factory()->create();

        // 出品者は別ユーザー
        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        // ログインする
        $this->actingAs($user);
        
        // いいねを実行する
        $this->post(route('items.like',$item->id));

        $response = $this->get(route('items.show',$item->id));

        $response->assertSee('images/ハートロゴ_ピンク.png');
    }

    public function test_user_can_unlike_other_users_item()
    {
        $user = User::factory()->create();

        // 出品者は別ユーザー
        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);
   
        // いいね済み状態を作る
        $user->likes()->create([
            'item_id' => $item->id,
        ]);

        // ログインする
        $this->actingAs($user);

        $response = $this->delete(route('items.unlike',$item->id));

        // DBからLikeが削除されたことを確認する
        $this->assertDatabaseMissing('likes',[
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);
    }

}
