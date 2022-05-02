<?php

namespace App\Providers;

use App\Models\BookVendor;
use App\Models\Collection;
use App\Models\Item;
use App\Models\Role;
use App\Models\User;
use App\Policies\BookVendorPolicy;
use App\Policies\CollectionPolicy;
use App\Policies\ItemPolicy;
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
        BookVendor::class => BookVendorPolicy::class,
        Item::class => ItemPolicy::class,
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
