<?php

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


/* Lists */
Route::get('/', function () {
    return view('welcome');
});

Route::get('list/', 'ListController@index')->name('list.index');

Route::get('getAllLists', [
	'as' => 'getAllLists',
	'uses' => 'ListController@getAllLists'
]);

Route::post('createList', [
	'as' => 'createList',
	'uses' => 'ListController@create'
]);

Route::post('add_member_url', [
	'as' => 'add_member_url',
	'uses' => 'ListController@addMembers'
]);


/* Campaign*/
Route::get('campaign/', 'CampaignController@index')->name('campaign.index');

Route::get('getAllCampaigns', [
	'as' => 'getAllCampaigns',
	'uses' => 'CampaignController@getAllCampaigns'
]);

Route::post('createCampaign', [
	'as' => 'createCampaign',
	'uses' => 'CampaignController@create'
]);

Route::post('add_scheduler_url', [
	'as' => 'add_scheduler_url',
	'uses' => 'CampaignController@addScheduler'
]);

//$dd= resolve('App\MailChimp\ExportSubscriber');
//dd($dd);

