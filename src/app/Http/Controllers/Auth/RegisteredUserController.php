<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use Illuminate\Auth\Events\Registered;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Support\Facades\Auth;


class RegisteredUserController extends Controller
{
    public function store(RegisterRequest $request,CreateNewUser $creator)
    {
        $validated = $request->validated();

        $user = $creator->create($validated);

        event(new Registered($user));

        Auth::login($user);

        return redirect('/mypage/profile');
    }
}
