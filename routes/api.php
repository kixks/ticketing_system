<?php

use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\TicketController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\ExpenseController;
use App\Http\Controllers\api\QrlogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login',[AuthController::class, 'login']);
Route::post('/register',[AuthController::class, 'register']);
Route::post('/qrlogs',QrlogController::class,'store');

Route::middleware(['auth:sanctum'])->group(function(){

    Route::post('/logout',[AuthController::class, 'logout']);
    Route::get('/profile',[UserController::class, 'profile']);
    Route::apiResource('tickets',TicketController::class);
    Route::apiResource('expenses',ExpenseController::class);
    
});




Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
