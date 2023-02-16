<?php

/**
 * GrantAccess.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\Http\Middleware;
use App\Model\Repositories\Contracts\UserRepository;
use App\User;
use Auth0\Login\Auth0JWTUser;

/**
 *
 * Description of GrantAccess
 *
 */

class GrantAccess
{
    /** @var UserRepository */
    private $repository;

    /**
     * GrantAccess constructor.
     *
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {
        /** @var User $user */
        $user = $this->repository->skipPresenter()->findWhere([])->first();
        \Auth::setUser(new Auth0JWTUser((object) [
            'sub' => $user->auth0_id
        ]));

        return $next($request);
    }
}
