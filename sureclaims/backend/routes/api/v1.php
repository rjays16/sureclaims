<?php

/**
 * v1.php
 *
 * @author Alvin Quinones <ajmquinones@gmail.com>
 * @copyright (c) 2018, Segworks Technologies Corporation
 *
 */

/**
 *
 * Description of v1
 *
 */

Route::get('/test', 'Controller@test');

Route::get('/supporting-documents/{uid}', 'SupportingDocumentsController@upload');
Route::post('/supporting-documents', 'SupportingDocumentsController@upload');
Route::post('/receive', 'Controller@receive');