<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;

class CommentTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_user_can_send_comment()
    {
        $user = User::factory()->create();

        // 出品者は別ユーザー
        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        // ログインする
        $this->actingAs($user);

        $response = $this->post(route('items.comment',$item->id),[
            'comment' => 'テストコメント',
        ]);

        // DBにCommentが保存される
        $this->assertDatabaseHas('comments',[
            'user_id' => $user->id,
            'item_id' => $item->id,
            'comment' => 'テストコメント',
        ]);

        // コメント数が増加する
        $this->assertEquals(1,$item->refresh()->comments()->count());
    }

    public function test_not_user_cannot_send_comment()
    {
        // 出品者は別ユーザー
        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        // ログイン前
        $this->assertGuest();

        $response = $this->post(route('items.comment',$item->id),['comment' => 'テストコメント',
        ]);

        // ログイン画面に遷移する
        $response->assertRedirect('/login');

        // DBにCommentが保存されていないことを確認する
        $this->assertDatabaseMissing('comments',[
            'item_id' => $item->id,
            'comment' => 'テストコメント',
        ]);
    }

    public function test_comment_is_required()
    {
        $user = User::factory()->create();
        
        // 出品者は別ユーザー
        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        
        // ログインする
        $this->actingAs($user);

        $response = $this->post(route('items.comment',$item->id),['comment' => '',
        ]);

       $response->assertSessionHasErrors(['comment']);

       // DBにCommentが保存されていないことを確認する
        $this->assertDatabaseMissing('comments',[
            'item_id' => $item->id,
            'comment' => '',
        ]);
    }


    public function test_comment_is_max255()
    {
        $user = User::factory()->create();
        
        // 出品者は別ユーザー
        $seller = User::factory()->create();

        $item = Item::factory()->create([
            'user_id' => $seller->id,
        ]);

        $longComment = str_repeat('あ',256);

        // ログインする
        $this->actingAs($user);

        $response = $this->post(route('items.comment',$item->id),['comment' => $longComment,
        ]);

       $response->assertSessionHasErrors(['comment']);

       // DBにCommentが保存されていないことを確認する
        $this->assertDatabaseMissing('comments',[
            'item_id' => $item->id,
            'comment' => $longComment,
        ]);
    }

}
