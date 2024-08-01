<?php

use App\Http\Controllers\Mails\MailsHy_lokController;
use App\Http\Controllers\Mails\MailsHylokController;
use App\Http\Controllers\Mails\MailsSwageloController;
use App\Http\Controllers\Mails\MailsWikaController;
use Illuminate\Support\Facades\Route;

Route::get('/mails/wika', [MailsWikaController::class, 'index'])->name('wika');
Route::post('/mails/wika', [MailsWikaController::class, 'general'])->name('wika.general');

Route::get('/mails/swagelo', [MailsSwageloController::class, 'index'])->name('swagelo');
Route::post('/mails/swagelo', [MailsSwageloController::class, 'general'])->name('swagelo.general');

Route::get('/mails/hylok', [MailsHylokController::class, 'index'])->name('hylok');
Route::post('/mails/hylok', [MailsHylokController::class, 'general'])->name('hylok.general');

Route::get('/mails/hy-lok', [MailsHy_lokController::class, 'index'])->name('hy-lok');

