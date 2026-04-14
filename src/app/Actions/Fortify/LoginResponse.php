<?php

namespace App\Actions\Fortify;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        // プロフィール未設定ならプロフィール設定画面へ
        if (!$user->profile_completed) {
            return redirect('/mypage/profile');
        }

        // 設定済みならトップへ
        return redirect('/');
    }
}
