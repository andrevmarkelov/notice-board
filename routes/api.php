<?php

use App\Http\Controllers\AdController;
use App\Http\Middleware\Cors;
use App\Http\Middleware\ForceJsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'v1', 'middleware' => [Cors::class, ForceJsonResponse::class]], function () {
    Route::get('ads/{slug?}/{limit?}', [AdController::class, 'index'])->name('ads.index.api');
    Route::get('ad/{id}', [AdController::class, 'show'])->name('ad.show.api');
    Route::post('ad', [AdController::class, 'store'])->name('ad.store.api');
});
