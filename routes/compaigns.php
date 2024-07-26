<?php

use App\Http\Controllers\Compaigns\CompaignsController;
use Illuminate\Support\Facades\Route;

Route::get('/compaigns/wika', [CompaignsController::class, 'indexWika'])->name('compaigns.wika');
Route::post('/compaigns/wika', [CompaignsController::class, 'invoiceClientByDirectWika'])->name('compaigns.wika.invoice');

Route::get('/compaigns/swagelo', [CompaignsController::class, 'indexSwagelo'])->name('compaigns.swagelo');
Route::post('/compaigns/swagelo', [CompaignsController::class, 'invoiceClientByDirectSwagelo'])->name('compaigns.swagelo.invoice');
