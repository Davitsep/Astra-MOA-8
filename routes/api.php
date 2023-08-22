<?php

use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\BrandController;
use App\Http\Controllers\API\EventPartnerController;
use App\Http\Controllers\API\DigitalController;
use App\Http\Controllers\API\EventController;
use App\Http\Controllers\API\EventParticipantController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [UserController::class, 'me']);
    Route::post('/logout', [UserController::class, 'logout']);
    Route::middleware('is.admin')->group(function () {
        Route::get('/admin', function () {
            return 'admin';
        });
    });
});

Route::get('/brands', [BrandController::class, 'index']);
Route::get('/brands/{id}', [BrandController::class, 'show']);
Route::post('/brands', [BrandController::class, 'store']);
Route::put('/brands/{id}', [BrandController::class, 'update']);
Route::delete('/brands/{id}', [BrandController::class, 'destroy']);
Route::get('/top-events', [EventPartnerController::class, 'getTopEventData']);
Route::get('/top-events-same-city', [EventPartnerController::class, 'getTopEventsWithSameCity']);
Route::post('/brands/filter', [BrandController::class, 'filter']);
Route::get('/event-details', [DigitalController::class, 'getEventDetails']);
Route::get('/digital/filter', [DigitalController::class, 'filter']);
Route::get('/digital/top-events', [DigitalController::class, 'getTopEvents']);
Route::post('/event-partners', [EventPartnerController::class, 'store']);
Route::get('/events-digital', [EventController::class, 'eventsDigital']);
Route::put('brands/{id}/increment-result', [BrandController::class, 'incrementResult']);

Route::get('event',[EventController::class, 'event']);
Route::get('/event/{id}', [EventController::class, 'getById']);
Route::post('event', [EventController::class, 'create']);
Route::put('/event/{id}', [EventController::class, 'update']);
Route::delete('/event/{id}', [EventController::class, 'delete']);
Route::get('/events/filter', [EventController::class, 'filter']);
Route::post('/event-participants', [EventParticipantController::class, 'create']);
