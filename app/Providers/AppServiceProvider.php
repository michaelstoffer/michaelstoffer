<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

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
        Inertia::share('site', [
            'name' => 'Michael Stoffer',
            'tagline' => "Engineer • Songwriter • Photographer",
            'url'  => config('app.url', 'https://www.michaelstoffer.com'),
            'location' => 'Myrtle Beach, SC',
            'email' => 'mstoffer@michaelstoffer.com',
            'socials' => [
                'github' => 'https://github.com/michaelstoffer',
                'linkedin' => 'https://www.linkedin.com/in/michaelstoffer/',
                'x' => 'https://x.com/michaelstoffer',
            ],
        ]);
    }
}
