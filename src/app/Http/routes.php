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



/*
 * 
 * Route Complex: User
 * 
 */

Route::get('/', [
    'as' => 'index',
    'uses' => 'UserController@index'
]);

Route::get('user/profile', [
    'as' => 'user.profile',
    'uses' => 'UserController@profile'
]);

Route::post('user/profile', [
    'as' => 'user.profile.save',
    'uses' => 'UserController@profileSave'
]);

Route::get('user/options', [
    'as' => 'user.options',
    'uses' => 'UserController@options'
]);

Route::post('user/options', [
    'as' => 'user.options.save',
    'uses' => 'UserController@optionsSave'
]);

Route::post('user/password', [
    'as' => 'user.password.save',
    'uses' => 'UserController@passwordSave'
]);

Route::get('lang/{lang}', [
    'as' => 'switch.language',
    'uses' => 'LanguageController@switchTo'
]);

Route::get('json/users/names', [
    'as' => 'json.users/names',
    'uses' => 'JsonRestController@allUserNamesExceptCurrentUser'
]);




/*
 * 
 * Route Complex: Match
 * 
 */

Route::get('match/new', [
    'as' => 'match.new',
    'uses' => 'MatchController@init'
]);

Route::post('match/create', [
    'as' => 'match.create',
    'uses' => 'MatchController@create'
]);

Route::get('match/join/{id}', [
    'as' => 'match.join.init',
    'uses' => 'MatchController@joinInit'
]);

Route::get('match', [
    'as' => 'match.goto',
    'uses' => 'MatchController@goToMatch'
]);

Route::post('match/join/{id}', [
    'as' => 'match.join.confirm',
    'uses' => 'MatchController@joinConfirm'
]);

Route::get('match/cancel/{id}', [
    'as' => 'match.cancel',
    'uses' => 'MatchController@cancel'
]);

Route::get('match/administrate/{id}', [
    'as' => 'match.administrate',
    'uses' => 'MatchController@administrate'
]);

Route::post('match/administrate/{id}', [
    'as' => 'match.administrate.save',
    'uses' => 'MatchController@saveAdministrate'
]);

Route::get('invitation/reject/{id}', [
    'as' => 'invitation.reject',
    'uses' => 'MatchController@rejectInvitation'
]);

Route::get('invitation/delete/{id}', [
    'as' => 'invitation.delete',
    'uses' => 'MatchController@deleteInvitation'
]);



/*
 * 
 * Route Complex: Messages
 * 
 */

Route::get('thread/new', [
    'as' => 'new.thread.init',
    'uses' => 'MessageController@initNewThreadWithNewMessage'
]);

Route::post('thread/new', [
    'as' => 'new.thread.create',
    'uses' => 'MessageController@newThreadWithNewMessage'
]);

Route::post('thread/{threadId}/newmessage', [
    'as' => 'thread.newmessage',
    'uses' => 'MessageController@newMessageInThread'
]);

Route::get('threads', [
    'as' => 'all.threads',
    'uses' => 'MessageController@showAllThreads'
]);

Route::get('thread/{threadId}', [
    'as' => 'thread.allmessages',
    'uses' => 'MessageController@showThread'
]);

Route::post('thread/{threadId}/addusers', [
    'as' => 'thread.addusers',
    'uses' => 'MessageController@addUsers'
]);

Route::get('thread/{threadId}/ajaxpart', [
    'as' => 'ajax.thread.part',
    'uses' => 'MessageController@loadThreadPart'
]);



Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);
