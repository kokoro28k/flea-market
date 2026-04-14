<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\Item;
use App\Models\User;
use App\Models\Category;


class SellTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_item_detail_can_save_on_sell_page()
    {
        // ユーザーを作成する
        $user = User::factory()->create();

        $category = Category::factory()->create();

        Storage::fake('public');

        // ログインする
        $this->actingAs($user);

        $response = $this->get('/sell');
        $response->assertStatus(200);

        $response = $this->post(route('items.store'),[
            'user_id' => $user->id,
            'category_id' =>[$category->id],
            'condition' => 1,
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => 'テスト用の商品です',
            'price' => 5000,
            'image_path' => UploadedFile::fake()->image('test.jpeg'),
        ]);

        // 商品一覧画面に遷移する
        $response->assertRedirect(route('items.index'));

        // itemを取得する
        $item = Item::first();

        $this->assertDatabaseHas('items',[
            'user_id' => $user->id,
            'condition' => 1,
            'name' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => 'テスト用の商品です',
            'price' => 5000,

        ]);

        // image_pathが保存されたかを確認する
        Storage::disk('public')->assertExists($item->image_path);

        $this->assertDatabaseHas('category_item',[
            'category_id' => $category->id,
            'item_id' => $item->id,
        ]);
    }
}
