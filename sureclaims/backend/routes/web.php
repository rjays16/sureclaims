<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::match(['get', 'post'],'/', function() {
    return File::get(public_path() . '/index.html');
});

Route::get('/supporting-documents/{uid}', 'SupportingDocumentsController@download');
Route::get('/claims/download/{id}.xml', 'ClaimController@downloadXML');
Route::get('/document/pbef/{id}', 'DocumentController@pbef');
Route::get('/document/byTransmittalPdf/{id}', 'DocumentController@byTransmittalPdf');
Route::get('/document/printCsf/{id}', 'DocumentController@printCsf');
Route::get('/document/byTransmittalExcel/{id}', 'DocumentController@byTransmittalExcel');
Route::get('/document/transmittalByMonth/{month}', 'DocumentController@transmittalByMonth');
Route::get('/document/transmittalByMonthRange/{month}', 'DocumentController@transmittalByMonthRange');
Route::get('/document/transmittalByCategory/{id}', 'DocumentController@transmittalByCategory');
Route::get('/document/totalPhicCategory/{data}', 'DocumentController@totalPhicCategory');
Route::get('/test', '@index');