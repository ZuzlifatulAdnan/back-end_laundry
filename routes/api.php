<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\BerandaController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PembayaranController;
use App\Http\Controllers\Api\RiwayatController;

// PUBLIC ROUTES
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('reset-password', [AuthController::class, 'resetPassword']);

// PROTECTED ROUTES - Requires Token via Sanctum
Route::middleware('auth:sanctum')->group(function () {

    // AUTH
    Route::post('/logout', [AuthController::class, 'logout']);

    // PROFILE
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile/update', [ProfileController::class, 'update']);
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword']);

    // DASHBOARD
    Route::get('/beranda', [BerandaController::class, 'index']);

    // ORDER
    Route::get('/order/mesin-ready', [OrderController::class, 'mesinReady']);
    Route::post('/order/selfservice', [OrderController::class, 'storeSelfservice']);
    Route::post('/order/dropoff', [OrderController::class, 'storeDropOff']);

    // PEMBAYARAN
    Route::get('/pembayaran/{id}', [PembayaranController::class, 'show']);
    Route::post('/pembayaran/{id}', [PembayaranController::class, 'update']);

    // RIWAYAT
    Route::get('/riwayat', [RiwayatController::class, 'index']);
    Route::get('/riwayat/{id}', [RiwayatController::class, 'show']);
});
