<?php

use App\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;
use App\Controllers\API\ReminderController;

/**
 * Use this file to define new API routes under the /api/... path
 * 
 * Here are some example, user related endpoints we have established as an example
 */

Route::get('/users/{id}', [UserController::class, 'read']);
Route::post('/users', [UserController::class, 'create']);

Route::post('/reminders', [ReminderController::class, 'create']);
Route::put('/reminders/{id}', [ReminderController::class, 'update']);
Route::delete('/reminders/{id}', [ReminderController::class, 'delete']);
