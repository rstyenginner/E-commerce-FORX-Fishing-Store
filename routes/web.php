<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

Route::inertia('/', 'welcome')->name('home');

Route::get('/dashboard', function () {
    $user = Auth::user();
    return match ($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'karyawan' => redirect()->route('karyawan.dashboard'),
        'pelanggan' => redirect()->route('pelanggan.home'),
        default => abort(403),
    };
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

require __DIR__.'/settings.php';

//admin
Route::middleware(['auth','verified','role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return Inertia::render('admin/dashboard');
        })->name('dashboard');
    });

//karyawan
Route::middleware(['auth','verified','role:karyawan'])
    ->prefix('karyawan')
    ->name('karyawan.')
    ->group(function () {
        Route::get('/dashboard', function () {
            return Inertia::render('karyawan/dashboard');
        })->name('dashboard');
    });

//pelanggan
Route::middleware(['auth','verified','role:pelanggan'])
    ->prefix('pelanggan')
    ->name('pelanggan.')
    ->group(function () {
        Route::get('/home', function () {
            return Inertia::render('pelanggan/home');
        })->name('home');
    });
