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

// TODO: Secure all of the routes for csrf and auth

Route::get('/', array('as' => 'home', 'uses' => 'HomeController@index'));

// Test taker view
Route::get('/{id}/{unique}', array('as' => 'take_test', 'uses' => 'TestController@take'))
	->where(array('id' => '[0-9]+', 'unique' => '[0-9a-zA-Z]{6}'));

// Allow the create view without being logged in
Route::get('/create', array('as' => 'create', 'uses' => 'TestsController@create'));

// Guest routes
Route::group(array('before' => 'guest'), function()
{
	Route::resource('/register', 'RegisterController');
	Route::resource('/login', 'LoginController');

	// Recover password
	Route::get('/recover', array('as' => 'recover', 'uses' => 'LoginController@recover'));
	Route::post('/recover', 'LoginController@recover_send');
	Route::get('/reset/{reset_code}', array('as' => 'reset', 'uses' => 'LoginController@reset_show'))
		->where(array('reset_code' => '[0-9a-zA-Z]{32,}'));
	Route::post('/reset/{reset_code}', 'LoginController@reset_password')
		->where(array('reset_code' => '[0-9a-zA-Z]{32,}'));
});

// Logged in routes
Route::group(array('before' => 'auth'), function()
{
	Route::resource('/account', 'AccountController');

	Route::get('/questiontemplate/{type}', array('as' => 'questiontemplate', 'uses' => 'QuestionTemplateController@show'))
		->where('type', '[a-z]+');

	// Create, edit, delete tests
	Route::resource('/tests', 'TestsController');
	// Presenter view
	Route::get('/test/{id}/{name}/{unique?}', array('as' => 'test', 'uses' => 'TestController@present'))
		->where(array('id' => '[0-9]+', 'name' => '[0-9a-zA-Z\-]+', 'unique' => '[0-9a-zA-Z]{32}'));
	// Sessions
	Route::resource('/sessions', 'SessionsController');

	Route::any('/logout', array('as' => 'logout', function(){
		return Helper::logout();
	}));
});

// API
Route::resource('/subjects','SubjectsController');
Route::post('/test/complete','TestController@complete');
Route::post('/test/answer','TestController@answer');
Route::get('/api/question/{test_id}/{isp}/{session}/{question_id?}','QuestionController@get')
	->where(array('test_id' => '[0-9]+', 'isp' => '0|1', 'session' => '[0-9a-zA-Z]{32}', 'question_id' => '[0-9]+'));
