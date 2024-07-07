<?php

use App\Http\Controllers\Mails\MailsController;
use Illuminate\Support\Facades\Route;

Route::get('/mails/wika', [MailsController::class, 'indexWika']);
Route::get('/mails/swagelo', [MailsController::class, 'indexSwagelo']);
Route::get('/mails/hylok', [MailsController::class, 'indexHylok']);

Route::post('/mails/wika', [MailsController::class, 'wikaGeneral'])->name('wika.general');
