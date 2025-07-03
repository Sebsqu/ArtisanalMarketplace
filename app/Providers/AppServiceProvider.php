<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repository\ProductRepositoryInterface;
use App\Repository\OrderRepositoryInterface;
use App\Repository\UserRepositoryInterface;
use App\Repository\AuthRepositoryInterface;
use App\Repository\AdminRepositoryInterface;
use App\Repository\ProductRepository;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use App\Repository\AuthRepository;
use App\Repository\AdminRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(AdminRepositoryInterface::class, AdminRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
