<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', array('as' => 'home', 'uses' => 'HomeController@index'));

Route::get('/questiontemplate/{type}', array('as' => 'questiontemplate', 'uses' => 'QuestionTemplateController@show'))
	->where('type', '[a-z]+');

// Create, edit, delete tests
Route::get('/create', array('as' => 'create', 'uses' => 'TestsController@create'));
Route::resource('/tests', 'TestsController');
// Presenter view
Route::get('/test/{id}/{name}/{unique?}', array('as' => 'test', 'uses' => 'TestController@present'))
	->where(array('id' => '[0-9]+', 'name' => '[0-9a-zA-Z\-]+', 'unique' => '[0-9a-zA-Z]{32}'));
// Test taker view
Route::get('/{id}/{unique}', array('as' => 'take_test', 'uses' => 'TestController@take'))
	->where(array('id' => '[0-9]+', 'unique' => '[0-9a-zA-Z]{6}'));

Route::resource('/signup', 'SignupController');
Route::resource('/login', 'LoginController');
Route::any('/logout', array('as' => 'logout', function(){
	return Helper::logout();
}));

// API
Route::resource('/subjects','SubjectsController');
Route::post('/test/complete','TestController@complete');
Route::get('/api/question/{test_id}/{question_id?}','QuestionController@get')
	->where(array('test_id' => '[0-9]+', 'question_id' => '[0-9]+'));
