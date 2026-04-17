<?php

namespace App\Providers;


use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Fortify;
use App\Actions\Fortify\LoginResponse;
use Laravel\Fortify\Contracts\VerifyEmailResponse;
use Illuminate\Support\Facades\Route;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
       $this->app->singleton(
        \Laravel\Fortify\Contracts\LoginResponse::class,
        LoginResponse::class);

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
        Fortify::loginView(function () {
            return view('auth.login');
        });

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;

            return Limit::perMinute(10)->by($email . $request->ip());
        });

        $this->app->singleton(VerifyEmailResponse::class,function () {
            return new class implements VerifyEmailResponse {
                public function toResponse($request)
                {
                    return redirect()->route('profiles.edit');
                }
            };
        });

    }
}  
