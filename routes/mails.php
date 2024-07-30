<?php

use App\Http\Controllers\Mails\MailsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['directWikaUpdate'])->group(function () {
    Route::get('/mails/wika', [MailsController::class, 'indexWika'])->name('wika');
});

Route::middleware(['directSwageloUpdate'])->group(function () {
    Route::get('/mails/swagelo', [MailsController::class, 'indexSwagelo'])->name('swagelo');
});

Route::post('/mails/swagelo', [MailsController::class, 'swageloGeneral'])->name('swagelo.general');

Route::middleware(['directHylokUpdate'])->group(function () {
    Route::get('/mails/hylok', [MailsController::class, 'indexHylok'])->name('hylok');
});


Route::get('/mails/hy-lok', [MailsController::class, 'indexHy_lok'])->name('hy-lok');

Route::post('/mails/wika', [MailsController::class, 'wikaGeneral'])->name('wika.general');
