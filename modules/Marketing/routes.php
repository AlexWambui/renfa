<?php

use Illuminate\Support\Facades\Route;
use Modules\Marketing\Http\Controllers\HomePageController;
use Modules\Marketing\Http\Controllers\ShopPageController;

Route::get('/', [HomePageController::class, 'index'])->name('home');
Route::get('/catalogue', [ShopPageController::class, 'index'])->name('catalogue-page.index');
