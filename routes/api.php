<?php

use App\Http\Controllers\OrganisationsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;



Route::prefix('auth')->group(function () {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
});

Route::middleware(['auth:api'])->group(function () {
    // Protected routes
    Route::get('/users/{id}', [UserController::class, 'show'])->whereUuid('id');
    Route::apiResource('organisations', OrganisationsController::class);
    Route::post('/organisations/{orgId}/users', [OrganisationsController::class, 'addUser']);
});
