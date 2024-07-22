<?php

use App\Http\Controllers\Compaigns\CompaignsController;
use Illuminate\Support\Facades\Route;

Route::get('/compaigns/wika', [CompaignsController::class, 'index'])->name('compaigns.wika');
