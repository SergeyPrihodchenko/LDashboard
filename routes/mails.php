<?php

use App\Http\Controllers\Mails\MailsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['directUpdate'])->group(function () {
    Route::get('/mails/wika', [MailsController::class, 'indexWika'])->name('wika');
});

Route::get('/mails/swagelo', [MailsController::class, 'indexSwagelo'])->name('swagelo');
Route::get('/mails/hylok', [MailsController::class, 'indexHylok'])->name('hylok');

Route::post('/mails/wika', [MailsController::class, 'wikaGeneral'])->name('wika.general');
