<?php

use App\Http\Controllers\Chart\ChartWikaController;
use App\Http\Controllers\Direct\DirectDownloadController;
use Illuminate\Support\Facades\Route;

Route::post('/chart/wika', [ChartWikaController::class, 'dataWikaByDate'])->name('chart.whika');
Route::post('/chart/wika/direct', [ChartWikaController::class, 'fetchDirectWika'])->name('chart.wika.direct');

Route::middleware('updateDirect')->group(function () {
    Route::get('/', [ChartWikaController::class, 'indexWika'])->name('chart.wika');
});


Route::get('/swagelo', [ChartWikaController::class, 'indexSwagelo'])->name('chart.swagelo');
Route::post('/chart/swagelo/direct', [ChartWikaController::class, 'fetchDirectSwagelo'])->name('chart.swagelo.direct');

Route::get('/hylok', [ChartWikaController::class, 'indexHylok'])->name('chart.hylok');
Route::post('/chart/hylok/direct', [ChartWikaController::class, 'fetchDirectHylok'])->name('chart.hylok.direct');

Route::get('/hy-lok', [ChartWikaController::class, 'indexHy_lok'])->name('chart.hy-lok');
// Route::post('/chart/swagelo/direct', [ChartWikaController::class, 'fetchDirectSwagelo'])->name('chart.hy-lok.direct');
