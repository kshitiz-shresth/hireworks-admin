<?php

use App\Http\Controllers\TemporaryController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/addDataRow',function(){
    return view('important.addDataRow');
});

Route::post('/postDataRow', [TemporaryController::class,'postData']);

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
