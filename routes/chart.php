<?php

use App\Http\Controllers\Chart\ChartWikaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ChartWikaController::class, 'indexWika']);
Route::post('/direct', [ChartWikaController::class, 'fetchDirect'])->name('wika.direct');
