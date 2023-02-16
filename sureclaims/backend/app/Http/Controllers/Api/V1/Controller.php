<?php

/**
 * Controller.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Controllers\SendsResponse;

/**
 *
 * Description of Controller
 *
 */

class Controller extends BaseController
{

    use SendsResponse;

    /**
     *
     */
    public function test()
    {

        $this->respondWithArray(['hello' => 'world']);
    }

    public function receive() 
    {
    	\Log::info($_FILES);
    	\Log::info($_POST);
    	$content = @file_get_contents($_FILES['attachment']['tmp_name']);
    	// \Log::info($content);
    	\Storage::disk('local')->put('sureclaims.pdf', $content);
    }
}
