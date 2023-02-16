<?php

/**
 * CurrentUserQuery.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Query;

use App\GraphQL\Concerns\ResolvesQueryFields;
use App\Lib\Auth\CurrentUser;
use App\Model\Repositories\Contracts\UserRepository;
use Auth0\Login\Auth0Service;
use Folklore\GraphQL\Support\Query;
use GraphQL;
use GraphQL\Type\Definition\ResolveInfo;

/**
 *
 * Description of CurrentUserQuery
 *
 */

class CurrentUserQuery extends Query
{
    use ResolvesQueryFields;

    /** @var UserRepository */
    protected $repository;

    /** @var Auth0Service  */
    protected $auth0;

    /**
     * UsersQuery constructor.
     *
     * @param array $attributes
     * @param UserRepository $memberRepository
     */
    public function __construct($attributes = [], UserRepository $memberRepository, Auth0Service $auth0)
    {
        $this->repository = $memberRepository;
        $this->auth0 = $auth0;
        parent::__construct($attributes);
    }

    /**
     * @var array
     */
    protected $attributes = [
        'name' => 'currentUser'
    ];

    /**
     * @return GraphQL\Type\Definition\Type
     */
    public function type()
    {
        return GraphQL::type('User');
    }

    /**
     * @return array
     */
    public function args()
    {
        return [];
    }


    /**
     * @param $root
     * @param array $args
     * @param $context
     * @param ResolveInfo $info
     *
     * @return mixed
     */
    public function resolve($root, $args, $context, ResolveInfo $info)
    {
        // Try to get the user information
        $user = app()->get(CurrentUser::class);
        if ($user === null) {
            return null;
        }

        $this->repository->resetScope();
        ['includes' => $includes, 'fieldSets' => $fieldSets] =
            $this->resolveQueryFields($info, 'user');


        $this->repository->parsePresenterParameters($fieldSets, $includes);
        return $this->repository->find($user->id);
    }
}
