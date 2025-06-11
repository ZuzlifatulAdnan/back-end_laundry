<?php

use App\Http\Controllers\BerandaController;
use App\Http\Controllers\KelolaOrderController;
use App\Http\Controllers\KelolaPembayaranController;
use App\Http\Controllers\MesinController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RiwayatPembayaranController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/beranda');

Route::middleware(['auth'])->group(function () {
  // beranda
  Route::resource('beranda', BerandaController::class);
  // Kelola Order
  Route::resource('kelolaOrder', KelolaOrderController::class);
  // kolal pembayran
  Route::resource('kelolaPembayaran', KelolaPembayaranController::class);
  // mesin
  Route::resource('mesin', MesinController::class);
  // Order
  Route::resource('order', OrderController::class);
  // Pembayaran
  Route::resource('pembayaran', PembayaranController::class);
  // riwayat pembayaran
  Route::resource('riwayatPembayaran', RiwayatPembayaranController::class);
  // User
  Route::resource('user', UserController::class);
  // Profile
  // Route::resource('profile', ProfileController::class);
  Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
  Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::post('/profile/update/{user}', [ProfileController::class, 'update'])->name('profile.update');
  Route::get('profile/change-password', [ProfileController::class, 'changePasswordForm'])->name('profile.change-password-form');
  Route::post('profile/change-password/{user}', [ProfileController::class, 'changePassword'])->name('profile.change-password');
});
