<?php

use App\Http\Controllers\Compaigns\CompaignsHy_LokController;
use App\Http\Controllers\Compaigns\CompaignsHylokController;
use App\Http\Controllers\Compaigns\CompaignsSwageloController;
use App\Http\Controllers\Compaigns\CompaignsWikaController;
use Illuminate\Support\Facades\Route;

Route::get('/compaigns/wika', [CompaignsWikaController::class, 'index'])->name('compaigns.wika');
Route::post('/compaigns/wika', [CompaignsWikaController::class, 'invoiceClientByDirect'])->name('compaigns.wika.invoice');

Route::get('/compaigns/swagelo', [CompaignsSwageloController::class, 'index'])->name('compaigns.swagelo');
Route::post('/compaigns/swagelo', [CompaignsSwageloController::class, 'invoiceClientByDirect'])->name('compaigns.swagelo.invoice');

Route::get('/compaigns/hylok', [CompaignsHylokController::class, 'index'])->name('compaigns.hylok');
Route::post('/compaigns/hylok', [CompaignsHylokController::class, 'invoiceClientByDirect'])->name('compaigns.hylok.invoice');

Route::get('/compaigns/hy-lok', [CompaignsHy_LokController::class, 'index'])->name('compaigns.hy-lok');
Route::post('/compaigns/hy-lok', [CompaignsHy_LokController::class, 'invoiceClientByDirect'])->name('compaigns.hy-lok.invoice');
