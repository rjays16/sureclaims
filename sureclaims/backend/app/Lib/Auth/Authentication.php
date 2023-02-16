<?php

/**
 * Authentication.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\Lib\Auth;

use App;
use App\Lib\Auth\Contracts\Authentication as AuthenticationInterface;
use App\Model\Repositories\Contracts\UserRepository;
use App\User;
use Auth0\SDK\API\Authentication as BaseAuthentication;
use Auth0\SDK\API\Header\Authorization\AuthorizationBearer;
use Auth0\SDK\API\Header\ContentType;
use Auth0\SDK\API\Helpers\ApiClient;
use Carbon\Carbon;
use Hyn\Tenancy\Contracts\CurrentHostname;
use Hyn\Tenancy\Contracts\Repositories\CustomerRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

/**
 *
 * Description of Authentication
 *
 */

class Authentication implements AuthenticationInterface
{
    const CACHE_API_CREDENTIALS = 'AUTH0_API_CREDENTIALS';

    /** @var array */
    private $auth0Config = [];

    /** @var array */
    private $credentials;

    /** @var BaseAuthentication */
    private $authApi;

    /** @var ApiClient */
    private $apiClient;

    /** @var UserRepository  */
    private $userRepository;

    /** @var CustomerRepository  */
    private $customerRepository;

    /**
     * Authentication constructor.
     */
    public function __construct(UserRepository $userRepository, CustomerRepository $customerRepository)
    {
        $auth0Config = config('laravel-auth0');
        $this->auth0Config = $auth0Config;
        $this->authApi = new BaseAuthentication(
            $auth0Config['domain'],
            $auth0Config['client_id'],
            $auth0Config['client_secret'],
            $auth0Config['api_identifier']
        );
        $this->userRepository = $userRepository;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @param string $id
     * @return User|array|Collection
     *
     * @throws \Exception
     * @throws \Illuminate\Container\EntryNotFoundException
     */
    public function user($id)
    {
        /** @var Collection $user */
        $user = $this->userRepository
            ->skipPresenter()
            ->findByField('auth0_id', $id)->first();

        $checkUpdate = false;
        if (App::environment() === 'production') {
            $daysLimit = 1;
            $checkUpdate = Carbon::now()->diffInDays($user->updated_at, false) <= -($daysLimit);
        }

        if (!$user || $checkUpdate) {
            $info = $this->apiClient()->get()
                ->users($id)
                ->call();

            $hostname = app()->get(CurrentHostname::class);
            $customerId = null;

            if (\App::runningUnitTests()) {
                // Enables unit testing without a valid hostname
                $customerId = -1;
            } else {
                foreach ($info['identities'] as $identity) {
                    if (@$identity['connection'] === @str_replace('.', '-', $hostname->fqdn)) {
                        $customerId = $hostname->customer_id;
                        break;
                    }
                }
            }


            if (!$customerId) {
                throw new \Exception('User is not associated with a valid customer account');
            }

            if (!$user) {
                $user = new User();
            }

            $user->auth0_id = $id;
            $user->customer_id = $customerId;
            $user->name = $info['name'];
            $user->nickname = $info['nickname'];
            $user->email = $info['email'];
            $user->email_verified = $info['email_verified'];
            $user->last_login = $info['last_login'];
            $user->last_ip = $info['last_ip'];
            $user->user_metadata = \GuzzleHttp\json_encode(
                @$info['user_metadata'] ?: [],
                JSON_OBJECT_AS_ARRAY
            );
            $user->app_metadata = \GuzzleHttp\json_encode(
                @$info['app_metadata'] ?: [],
                JSON_OBJECT_AS_ARRAY
            );
            $user->save();
        }

        return $user;
    }

    /**
     * @return array
     *
     * @
     */
    private function clientCredentials()
    {
        $this->credentials = \Cache::get(self::CACHE_API_CREDENTIALS);
        if (!$this->credentials) {
            $this->credentials = $this->authApi->client_credentials($this->auth0Config);
            \Cache::set(self::CACHE_API_CREDENTIALS, $this->credentials, now()->addSeconds($this->credentials['expires_in']));
        }
        return $this->credentials;
    }

    /**
     * @return ApiClient|null
     */
    private function apiClient()
    {
        if ($this->apiClient === null) {
            $credentials = $this->clientCredentials();
            $config = [
                'domain' => "https://{$this->auth0Config['domain']}",
                'basePath' => '/api/v2/',
                'headers' => [
                    new ContentType('application/json'),
                    new AuthorizationBearer($credentials['access_token'])
                ],
                'guzzleOptions' => $this->auth0Config['guzzle_options'],
            ];
            $this->apiClient = new ApiClient($config);
        }

        return $this->apiClient;
    }
}