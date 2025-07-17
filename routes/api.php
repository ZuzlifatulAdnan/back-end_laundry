<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\BerandaController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PembayaranController;
use App\Http\Controllers\Api\RiwayatController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);

    Route::middleware('auth:sanctum')->group(function () {
        // auth logout
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // PROFILE
        Route::get('/profile', [ProfileController::class, 'show']);
        Route::post('/profile/update', [ProfileController::class, 'update']);
        Route::post('/profile/change-password', [ProfileController::class, 'changePassword']);

        // BERANDA
        Route::get('/beranda', [BerandaController::class, 'index']);

        // ORDER
        Route::post('/order/selfservice', [OrderController::class, 'storeSelfservice']);
        Route::post('/order/dropoff', [OrderController::class, 'storeDropOff']);

        // PEMBAYARAN
        Route::get('/pembayaran/{id}', [PembayaranController::class, 'show']);
        Route::post('/pembayaran/{id}', [PembayaranController::class, 'update']);

        // RIWAYAT
        Route::get('/riwayat', [RiwayatController::class, 'index']);
        Route::get('/riwayat/{id}', [RiwayatController::class, 'show']);
    });
});
