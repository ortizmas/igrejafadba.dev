<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Recurso;
// use App\Libraries\DwAcl;
// use Auth;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // $permissions = Recurso::with('perfiles')->get();
        // foreach ($permissions as $permission) {
        //     \Gate::define($permission->recurso, function(User $user) use ($permission){
        //         return $user->hasPermission($permission);
        //     });
        // }
    }
}
