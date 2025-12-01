<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    protected $policies = [
    
        \App\Models\Movie::class => \App\Policies\MoviePolicy::class,
        \App\Models\Comment::class => \App\Policies\CommentPolicy::class,
    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
