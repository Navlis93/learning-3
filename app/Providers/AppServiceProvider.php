<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Todoitem;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Gate::define('update-todo', function (User $user, Todoitem $item) {
            return $user->id == $item->user_id;
        });
    }
}
