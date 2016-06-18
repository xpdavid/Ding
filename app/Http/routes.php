<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/forcelogin', function() {
    Auth::loginUsingId(1);
    return redirect('/question');
});

Route::get('/', function () {
    return view('auth.index');
});

// for auth controller
Route::auth();


Route::get('/home', 'HomeController@index');


// for question controller
Route::get('/question', 'QuestionController@index');
Route::get('/question/{question_id}', 'QuestionController@show');

// for topic controller
Route::controller('/topic', 'TopicController');

// for user controller
Route::get('/people/edit', 'PeopleController@edit');
Route::get('/people/{url_name}', 'PeopleController@show');
Route::post('/people/update', 'PeopleController@update');
Route::post('/people/delete', 'PeopleController@destroy');

// for message controller
Route::resource('/inbox', 'InboxController');

// for reply controller
Route::controller('/reply', 'ReplyController');

// for API controller
Route::controller('/api', 'APIController');