<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_email_is_requires()
    {
        $response = $this->post('/login',[
            'email' => '',
            'password' => 'password',        
            ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_password_is_required()
    {
         $response = $this->post('/login',[
            'email' => 'testcase@example.com',
            'password' => '',
            'password_confirmation' => '',
            ]);

        $response->assertSessionHasErrors(['password']);
    }

    // 入力情報が間違っている場合を、ログイン失敗時とする
    public function test_login_fails_with_wrong_password()
    {
        // 新しいユーザーを作成
        $user = User::factory()->create([
            'email' => 'testcase1@example.com',
            'password' => bcrypt('password123'),
        ]);

        // パスワードだけ間違えてログインを試す
         $response = $this->post('/login',[
            'email' => 'testcase1@example.com',
            'password' => 'wrongpassword'
            ]);

        // 認証失敗時は、emailにエラーが入る
        $response->assertSessionHasErrors(['email']);
        
        // ログインしていないことを確認　認証されていない
        $this->assertGuest();
    }

    //ログアウト機能
      public function test_user_can_logout()
    {
        // 新しいユーザーを作成してログイン
        $user = User::factory()->create();
        $this->actingAs($user);
   
        // ユーザーのログインを確認
        $this->assertAuthenticatedAs($user);
 
        // ログアウトする
        $response = $this->post('/logout');
        $response->assertRedirect('/');
        
        // 認証されていないことを確認　
        $this->assertGuest();
    }
}
