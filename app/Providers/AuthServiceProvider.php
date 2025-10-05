<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Policies\UserPolicy;
use App\Policies\PropertyPolicy;
use App\Models\Property;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Mapeia o nosso Model 'User' para a nossa Policy 'UserPolicy'
        User::class => UserPolicy::class,
        Property::class => PropertyPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // O Laravel automaticamente descobre e registra as policies
        // definidas na propriedade $policies.
    }
}
