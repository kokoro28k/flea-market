<?php

namespace App\Http\Controllers\Auth;
use App\Http\Requests\Auth\LoginRequest;
use Laravel\Fortify\Contracts\LoginResponse;
use Illuminate\Pipeline\Pipeline;

class CustomAuthenticatedSessionController
{
  
    public function create()
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request)
    {
        return app(Pipeline::class)
            ->send($request)
            ->through([
                \Laravel\Fortify\Actions\AttemptToAuthenticate::class,
                \Laravel\Fortify\Actions\PrepareAuthenticatedSession::class,
            ])
            ->then(function ($request) {
                return app(\Laravel\Fortify\Contracts\LoginResponse::class);

            });
    }
}



