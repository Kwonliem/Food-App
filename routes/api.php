<?php

use App\Http\Controllers\API\FoodController;
use App\Http\Controllers\API\MidtransController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:sanctum')->group(function () {
    Route::get('user',[UserController::class,'fetch']);
    Route::post('user',[UserController::class,'updateProfile']);
    Route::post('user/photo',[UserController::class,'updatePhoto']);
    Route::post('logout',[UserController::class,'logout']);

    // End Point checkOut

    Route::post('checkout',[TransactionController::class,'checkout']);

    // End Point Transaction
    Route::get('transaction',[TransactionController::class,'all']);
    Route::post('transaction/{id}',[TransactionController::class,'update']);
});

// User Controller
Route::post('login',[UserController::class,'login']);
Route::post('register',[UserController::class,'register']);

// Food Controller
Route::get('food', [FoodController::class, 'all']);

// Midtrans Controller
Route::post('midtrans/callback',[MidtransController::class,'callback']);