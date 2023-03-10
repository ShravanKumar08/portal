<?php
/*
  |--------------------------------------------------------------------------
  | Poll Routes
  |--------------------------------------------------------------------------
  |
 */

Route::middleware(['role:admin|super-user', 'admin', 'web'])->prefix(config('larapoll_config.prefix'))->group(function(){
    Route::get('polls', ['uses' => 'PollManagerController@index', 'as' => 'poll.index']);
    Route::get('polls/create', ['uses' => 'PollManagerController@create', 'as' => 'poll.create']);
    Route::get('polls/{poll}', ['uses' => 'PollManagerController@edit', 'as' => 'poll.edit']);
    Route::post('polls/{poll}', ['uses' => 'PollManagerController@update', 'as' => 'poll.update']);
    Route::delete('polls/{poll}', ['uses' => 'PollManagerController@remove', 'as' => 'poll.remove']);
    Route::patch('polls/{poll}/lock', ['uses' => 'PollManagerController@lock', 'as' => 'poll.lock']);
    Route::patch('polls/{poll}/unlock', ['uses' => 'PollManagerController@unlock', 'as' => 'poll.unlock']);
    Route::post('polls', ['uses' => 'PollManagerController@store', 'as' => 'poll.store']);
    Route::get('polls/{poll}/options/add', ['uses' => 'OptionManagerController@push', 'as' => 'poll.options.push']);
    Route::post('polls/{poll}/options/add', ['uses' => 'OptionManagerController@add', 'as' => 'poll.options.add']);
    Route::get('polls/{poll}/options/remove', ['uses' => 'OptionManagerController@delete', 'as' => 'poll.options.remove']);
    Route::delete('polls/{poll}/options/remove', ['uses' => 'OptionManagerController@remove', 'as' => 'poll.options.remove']);
});

Route::post('/vote/polls/{poll}', ['uses' => 'VoteManagerController@vote', 'as' => 'poll.vote']);
