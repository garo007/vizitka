<?php

use App\Http\Controllers\API\Admin\ClientController;
use App\Http\Controllers\API\Admin\IndexController as AdminIndexController;
use App\Http\Controllers\API\Admin\NewsController;
use App\Http\Controllers\API\Admin\TagController;
use App\Http\Controllers\API\Auth\AdminController as AuthAdminController;
use App\Http\Controllers\API\Auth\ClientController as AuthClientController;
use App\Http\Controllers\API\Client\AirTicketController;
use App\Http\Controllers\API\Client\ContractController;
use App\Http\Controllers\API\Client\IndexController as ClientIndexController;
use App\Http\Controllers\API\Client\RubricController;
use App\Http\Controllers\API\Client\SendRequestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('client')->group(function () {
    Route::post('join', [AuthClientController::class, 'join']);
    Route::post('create-password', [AuthClientController::class, 'createPassword']);
    Route::post('login', [AuthClientController::class, 'login']);
    Route::post('logout', [AuthClientController::class, 'logout']);
    Route::post('me', [ClientIndexController::class, 'me']);
    Route::post('update', [ClientIndexController::class, 'update']);
    Route::post('change-password', [ClientIndexController::class, 'changePassword']);
    Route::post('forgot-password', [ClientIndexController::class, 'forgotPassword']);
    Route::post('reset-password', [ClientIndexController::class, 'resetPassword']);
    Route::post('send-request', [SendRequestController::class, 'sendRequest']);
    Route::get('contract', [ContractController::class, 'index']);
    Route::get('air-ticket', [AirTicketController::class, 'index']);
    Route::post('air-ticket', [AirTicketController::class, 'store']);
    Route::put('air-ticket/{airTicket}', [AirTicketController::class, 'update']);
    Route::delete('air-ticket/{airTicket}', [AirTicketController::class, 'delete']);
    Route::resource('rubrics', RubricController::class);
});

Route::prefix('admin')->group(function () {
    Route::post('login', [AuthAdminController::class, 'login']);
    Route::post('logout', [AuthAdminController::class, 'logout']);
    Route::resource('client', ClientController::class);
    Route::resource('rubrics', RubricController::class);
    Route::apiResource('news', NewsController::class);
    Route::apiResource('tags', TagController::class);

//    Route::get('client', [ClientController::class, 'index']);
//    Route::get('client{client}/show', [ClientController::class, 'show']);
//    Route::delete('client/{client}', [ClientController::class, 'delete']);
    Route::post('me', [AdminIndexController::class, 'me']);
    Route::post('change-password', [AdminIndexController::class, 'changePassword']);
});
