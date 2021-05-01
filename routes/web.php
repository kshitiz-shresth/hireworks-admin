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

//Questions
Route::group(
    ['prefix' => 'myadmin', 'as' => 'myadmin.'],
    function(){
        Route::get('questions/data', 'AdminQuestionController@data')->name('questions.data');
        Route::resource('questions', 'AdminQuestionController');
        Route::get('company/changlang', ['as' => 'company.langchange', 'uses' => 'AdminCompanyController@changelangfun']);
        Route::post('questions/update', 'AdminQuestionController@update')->name('questions.update');
        Route::delete('questions/destroyAssQuestion/{id}', 'AdminQuestionController@destroyAssQuestion')->name('questions.destroyAssQuestion');
        
        //Assessments
        Route::get('assessments/data', 'AdminAssessmentController@data')->name('assessments.data');
        Route::get('assessments/fetchassessments', 'AdminAssessmentController@fetchassessments')->name('assessments.fetchassessments');
        Route::get('assessments/fetchAssessementQuestion', 'AdminAssessmentController@fetchAssessementQuestion')->name('assessments.fetchAssessementQuestion');
        Route::resource('assessments', 'AdminAssessmentController');
        Route::post('assessments/saveAssessment', 'AdminAssessmentController@saveAssessment')->name('assessments.saveAssessment');        
    }
);



Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});
