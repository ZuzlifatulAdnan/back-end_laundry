<?php

use App\Http\Controllers\BerandaController;
use App\Http\Controllers\KelolaOrderController;
use App\Http\Controllers\KelolaPembayaranController;
use App\Http\Controllers\MesinController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RiwayatController;
use App\Http\Controllers\RiwayatPembayaranController;
use App\Http\Controllers\UserController;
use Hamcrest\Number\OrderingComparison;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/beranda');

Route::middleware(['auth'])->group(function () {
  // beranda
  Route::resource('beranda', BerandaController::class);
  // Kelola Order
  Route::get('/kelolaOrder/proses', [KelolaOrderController::class, 'showProses'])->name('kelolaOrder.proses');
  Route::put('/kelolaOrder/update-status/{id}', [KelolaOrderController::class, 'updateStatus'])->name('kelolaOrder.updateStatus');
  Route::get('/kelolaOrder/diterima', [KelolaOrderController::class, 'showDiterima'])->name('kelolaOrder.diterima');
  // Route::put('/kelolaOrder/update-status/{id}', [KelolaOrderController::class, 'updateStatus'])->name('kelolaOrder.updateStatus');
  Route::put('/kelolaOrder/auto-batal/{id}', [KelolaOrderController::class, 'autoBatal']);

  Route::resource('kelolaOrder', KelolaOrderController::class);

  // kolal pembayran
  Route::resource('kelolaPembayaran', KelolaPembayaranController::class);

  // mesin
  Route::resource('mesin', MesinController::class);
  // Order
  Route::get('/order/selfservice', [OrderController::class, 'selfservice'])->name('order.selfservice');
  Route::post('/order/selfservice', [OrderController::class, 'storeSelfservice'])->name('order.storeSelfservice');
  Route::get('/order/dropoff', [OrderController::class, 'dropoff'])->name('order.dropoff');
  Route::post('/order/dropoff', [OrderController::class, 'storeDropoff'])->name('order.storeDropoff');
  // Pembayaran
  Route::resource('pembayaran', PembayaranController::class);
  // riwayat pembayaran
  Route::resource('riwayat', RiwayatController::class);
  // User
  Route::resource('user', UserController::class);
  // Profile
  Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
  Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
  Route::post('/profile/update/{user}', [ProfileController::class, 'update'])->name('profile.update');
  Route::get('profile/change-password', [ProfileController::class, 'changePasswordForm'])->name('profile.change-password-form');
  Route::post('profile/change-password/{user}', [ProfileController::class, 'changePassword'])->name('profile.change-password');
});
