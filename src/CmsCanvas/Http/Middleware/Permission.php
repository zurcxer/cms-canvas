<?php 

namespace CmsCanvas\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class Permission {

    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (! $this->auth->guest()) {
            $actions = $request->route()->getAction();
            $permissions = (isset($actions['permission'])) ? (array) $actions['permission'] : [];

            foreach ($permissions as $permission) {
                $this->auth->user()->checkPermission($permission);
            }
        }

        return $next($request);
    }

}
