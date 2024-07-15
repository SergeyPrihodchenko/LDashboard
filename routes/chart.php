<?php

use App\Http\Controllers\Chart\ChartWikaController;
use App\Http\Controllers\Direct\DirectDownloadController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ChartWikaController::class, 'indexWika'])->name('chart.wika');

Route::post('/direct', [ChartWikaController::class, 'fetchDirect'])->name('wika.direct');

Route::post('/chart/wika', [ChartWikaController::class, 'dataWikaByDate'])->name('chart.whika');