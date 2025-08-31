<?php

use App\Http\Controllers\Api\MessengerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('messenger')->group(function () {
    Route::get('/webhook', [MessengerController::class, 'verify']);
    Route::post('/webhook', [MessengerController::class, 'handle']);
});
