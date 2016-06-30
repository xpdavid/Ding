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
    return redirect('/highlight');
});

// for auth controller
Route::auth();

// user index controller
Route::get('/', 'UserCenterController@home');
Route::post('/home', 'UserCenterController@postHome');

// for highlight controller
Route::get('/highlight', 'HighlightController@index');
Route::controller('/highlight', 'HighlightController');

// for answer controller
Route::post('/question/answers', 'AnswerController@postAnswers');

// for question & answer
Route::get('/question/{question_id}', 'QuestionController@show');
Route::post('/question/{question_id}/answer', 'AnswerController@storeAnswer');
Route::post('/question/ask', 'QuestionController@ask');

// for answer
Route::post('/answer/{answer_id}/vote', 'AnswerController@vote');
Route::get('/question/{question_id}/answer/{answer_id}', 'AnswerController@show');

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

// for bookmark
Route::get('/people/{url_name}/bookmark', [ 'as' => 'people.bookmark', 'uses' => 'BookmarkController@index']);
Route::post('/bookmark', 'BookmarkController@postBookmark');
Route::post('/bookmark/create', 'BookmarkController@create');
Route::post('/bookmark/operation', 'BookmarkController@operation');
Route::post('/bookmark/hot', 'BookmarkController@hot');
Route::get('/bookmark/{id}', 'BookmarkController@show');
Route::post('/bookmark/{id}', 'BookmarkController@postShow');
Route::post('/bookmark/{id}/update', 'BookmarkController@update');
Route::post('/bookmark/{id}/delete', 'BookmarkController@delete');

// for user controller
Route::get('/people/edit', 'PeopleController@edit');
Route::get('/people/{url_name}', 'PeopleController@show');
Route::post('/people/update', 'PeopleController@update');
Route::post('/people/delete', 'PeopleController@destroy');


// for message controller
Route::resource('/inbox', 'InboxController');


// for reply controller
Route::get('/reply/{reply_id}', 'ReplyController@show');
Route::controller('/reply', 'ReplyController');

// for subscribe controller
Route::controller('/subscribe', 'SubscribeController');

// for user center controller
Route::get('/notification', 'UserCenterController@notification');
Route::post('/notification', 'UserCenterController@postNotification');
Route::get('/subscribed', 'UserCenterController@subscribed');
Route::post('/subscribed', 'UserCenterController@postSubscribed');
Route::get('/invitation', 'UserCenterController@invitation');
Route::post('/invitation', 'UserCenterController@postInvitation');

// for notification
Route::post('/notification/operation', 'NotificationController@operation');

// for API controller
Route::controller('/api', 'APIController');

// for user setting
Route::resource('/settings', 'SettingsController');

// for search
Route::get('/search', ['as' => 'search', 'uses' => 'SearchController@index']);
Route::post('/search', 'SearchController@postSearch');


// for dynamic image system
Route::get('/image/{reference_id}/{width}/{height}', 'ImageController@image');

