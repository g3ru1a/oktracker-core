<?php

namespace App\Providers;

use App\Models\Collection;
use App\Models\Role;
use App\Models\User;
use App\Policies\CollectionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Collection::class => CollectionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('access_management', fn(User $user) => in_array($user->role_id, [Role::ADMIN, Role::DATA_ANALYST]));
    }
}
