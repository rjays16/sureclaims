<?php

/**
 * OAuthTokenType.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as BaseType;
use GraphQL;

/**
 *
 * Description of OAuthTokenType
 *
 */

class OAuthTokenType extends BaseType
{
    protected $attributes = [
        'name' => 'OAuthToken',
        'description' => 'Token issued on successful authorization request'
    ];

    /**
     * @return array
     */
    public function fields()
    {
        return [
            'token_type' => [
                'type' => Type::string(),
                'description' => 'The type of authorize token issued'
            ],
            'expires_in' => [
                'type' => Type::int(),
                'description' => 'The timestamp for the expiration of the access token',
            ],
            'access_token' => [
                'type' => Type::string(),
                'description' => 'The access token'
            ],
            'refresh_token' => [
                'type' => Type::string(),
                'description' => 'The refresh token',
            ],
            'error_code' => [
                'type' => Type::string(),
                'description' => 'Error code'
            ],
            'error_message' => [
                'type' => Type::string(),
                'description' => 'Error message'
            ],
            'error_hint' => [
                'type' => Type::string(),
                'description' => 'Error hint'
            ],
        ];
    }

}
