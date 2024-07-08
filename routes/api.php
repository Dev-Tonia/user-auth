<?php

use App\Http\Controllers\OrganisationsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;





Route::middleware(['auth:api'])->group(function () {
    // Protected routes
    Route::get('/users/{id}', [UserController::class, 'show'])->whereUuid('id');
    Route::apiResource('organisations', OrganisationsController::class);
});
