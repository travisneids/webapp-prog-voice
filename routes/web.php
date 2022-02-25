<?php

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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/podium', function () {
    return view('podium');
})->middleware(['auth'])->name('podium');

Route::post('/conference/connect/client',
    [
        'uses' => 'ConferenceController@connectClient', 'as' => 'connect-conference-client'
    ]);
Route::post('conference/wait',
    ['uses' => 'ConferenceController@wait', 'as' => 'conference-wait']
);
Route::post('sync/webhook',
    ['uses' => 'ConferenceController@syncWebhook', 'as' => 'sync-webhook']
);
Route::post('conference/status-callback',
    ['uses' => 'ConferenceController@statusCallback', 'as' => 'status-callback']
);
Route::post('conference/event-callback',
    ['uses' => 'ConferenceController@eventCallback', 'as' => 'event-callback']
);
Route::get('conference/hold',
    ['uses' => 'ConferenceController@hold', 'as' => 'conference-hold']
);
Route::post('sip/call-status-changed',
    ['uses' => 'ConferenceController@callStatusChanged', 'as' => 'call-status-changed']
);
Route::post(
    '/token',
    ['uses' => 'TokenController@getTokenV2', 'as' => 'new-token']
);
Route::post('conference/connect/{conference_id}/worker1',
    ['uses' => 'ConferenceController@connectWorker1', 'as' => 'conference-connect-worker1']
);
Route::post('conference/connect/{conference_id}/sipWorker1',
    ['uses' => 'ConferenceController@connectSipWorker1', 'as' => 'conference-connect-sipWorker1']
);
require __DIR__.'/auth.php';
