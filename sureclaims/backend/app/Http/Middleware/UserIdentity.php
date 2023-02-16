<?php

namespace App\Http\Middleware;

use App\Lib\Auth\Contracts\Authentication;
use App\Lib\Auth\CurrentUser;
use Closure;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Facades\Auth;

class UserIdentity
{
    /** @var Authentication */
    protected $auth;

    /**
     * UserIdentity constructor.
     */
    public function __construct()
    {
        $this->auth = app()->get(Authentication::class);
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            // Checks if Current User has already been set
            \App::make(CurrentUser::class);
            return $next($request);
        } catch (BindingResolutionException $e) {
            //
        }

        $authUser = Auth::user();
        $user = $this->auth->user($authUser->sub);

        app()->singleton(CurrentUser::class, function () use ($user) {
            return $user;
        });

        return $next($request);
    }
}
