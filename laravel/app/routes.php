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
Route::get('/form', array('as' => 'form', 'uses' => 'FormController@index'));
Route::get('/create', array('as' => 'create', 'uses' => 'TestsController@index'));

Route::get('/questiontemplate/{type}', array('as' => 'questiontemplate', 'uses' => 'QuestionTemplateController@show'))
	->where('type', '[a-z]+');
Route::resource('/tests', 'TestsController');

Route::resource('/signup', 'SignupController');
Route::resource('/login', 'LoginController');
Route::any('/logout', array('as' => 'logout', function(){
	return Helper::logout();
}));

// API
Route::resource('/subjects','SubjectsController');
