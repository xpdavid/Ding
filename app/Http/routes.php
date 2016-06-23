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

Route::get('/forcelogin/{id}', function($id) {
    Auth::loginUsingId($id);
    return redirect('/question');
});

Route::get('/', function () {
    return view('auth.index');
});

// for auth controller
Route::auth();


Route::get('/home', 'HomeController@index');


// for answer controller
Route::post('/question/answers', 'AnswerController@postAnswers');

// for question & answer
Route::get('/question', 'QuestionController@index');
Route::get('/question/{question_id}', 'QuestionController@show');
Route::post('/question/{question_id}/answer', 'AnswerController@storeAnswer');
Route::post('/question/ask', 'QuestionController@ask');

// for answer
Route::post('/answer/{answer_id}/vote', 'AnswerController@vote');

// for reply
Route::post('/reply/reply-list', 'ReplyController@replyList');
Route::post('/reply/conversation', 'ReplyController@showConversation');
Route::post('/reply/{item_id}/reply', 'ReplyController@storeReply');
Route::post('/reply/{reply_id}/vote', 'ReplyController@vote');
Route::post('/reply/{reply_id}', 'ReplyController@storeCommentReply');

// for topic controller
Route::get('/topic', 'TopicController@index');
Route::get('/topics', 'TopicController@topics');
Route::post('/topic/{topic_id}/sub_topics', 'TopicController@subTopics');
Route::get('/topic/{topic_id}', 'TopicController@show');
Route::get('/topic/{topic_id}/edit', 'TopicController@edit');
Route::post('/topic/{topic_id}/update', 'TopicController@update');
Route::get('/topic/{topic_id}/organization', 'TopicController@organization');
Route::post('/topic/questions', 'TopicController@getQuestions');

// for user controller
Route::get('/people/edit', 'PeopleController@edit');
Route::get('/people/{url_name}', 'PeopleController@show');
Route::post('/people/update', 'PeopleController@update');
Route::post('/people/delete', 'PeopleController@destroy');

// for message controller
Route::resource('/inbox', 'InboxController');

// for reply controller
Route::controller('/reply', 'ReplyController');

// for subscribe controller
Route::controller('/subscribe', 'SubscribeController');

// for API controller
Route::controller('/api', 'APIController');