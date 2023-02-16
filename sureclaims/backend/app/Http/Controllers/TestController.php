<?php

/**
 * TestController.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\Http\Controllers;
use Illuminate\Http\Response;
// use Laravel\Passport\Http\Controllers\AccessTokenController;
use Psr\Http\Message\ServerRequestInterface;
use App\Http\Controllers\Controller as BaseController;

/**
 *
 * Description of TestController
 *
 */

class TestController extends BaseController
{


    public function index()
    {
        // phpinfo();
    	throw new \Exception('Test Sentry:' . time());
        ~s(config('eclaims.hospitalCode'));
////        $request = app(ServerRequestInterface::class);
////        $request = $request->withParsedBody([
////            'grant_type' => 'password',
////            'client_id' => 2,
////            'client_secret' => '7rkXhBMNJ0q0UcQWUTWuSAIr2Pkg4404N2ECEXwW',
////            'username' => 'ygoldner@example.com',
////            'password' => 'secret',
////            'scope' => '*',
////        ]);
////        /** @var Response $response */
////        $response = app(AccessTokenController::class)->issueToken($request);
////        s($response->getContent());
//
//        $result = [
//            'tokenType' => null,
//            'expiresIn' => null,
//            'accessToken' => null,
//            'refreshToken' => null,
//            'errorCode' => null,
//            'errorMessage' => null,
//            'errorHint' => null,
//        ];
//
//        $request = app(ServerRequestInterface::class);
//        $request = $request->withParsedBody([
//            'grant_type' => 'password',
//            'client_id' => 2,
//            'client_secret' => '7rkXhBMNJ0q0UcQWUTWuSAIr2Pkg4404N2ECEXwW',
//            'username' => 'ygoldner@example.com',
//            'password' => 'secret',
//            'scope' => '*',
//        ]);
//
//        /** @var Response $response */
//        $response = app(AccessTokenController::class)->issueToken($request);
//        $responseObject = json_decode($response->getContent(), true);
//        if ($responseObject === null) {
//            return array_merge($result, [
//                'errorCode' => 'invalid_response',
//                'errorMessage' => 'Response returned by the authorization server '.
//                    'is not in the expected format',
//            ]);
//        }
//
//        $result = array_merge($result, [
//            'tokenType' => @$responseObject['token_type'],
//            'expiresIn' => @$responseObject['expires_in'],
//            'accessToken' => @$responseObject['access_token'],
//            'refreshToken' => @$responseObject['refresh_token'],
//            'errorCode' => @$responseObject['error'],
//            'errorMessage' => @$responseObject['message'],
//            'errorHint' => @$responseObject['hint'],
//        ]);
//
//        s($result);
    }
}
