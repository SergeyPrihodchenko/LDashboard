<?php

use App\Http\Controllers\Chart\ChartHy_LokController;
use App\Http\Controllers\Chart\ChartHylokController;
use App\Http\Controllers\Chart\ChartSwageloController;
use App\Http\Controllers\Chart\ChartWikaController;
use Illuminate\Support\Facades\Route;


Route::get('/', [ChartWikaController::class, 'index'])->name('chart.wika');
Route::post('/chart/wika', [ChartWikaController::class, 'dataByDate'])->name('chart.wika.byDate');
Route::post('/chart/wika/direct', [ChartWikaController::class, 'fetchDirect'])->name('chart.wika.direct');
Route::post('/chart/wika/castomMetric', [ChartWikaController::class, 'getCastomMetric'])->name('chart.wika.castom');


Route::get('/swagelo', [ChartSwageloController::class, 'index'])->name('chart.swagelo');
Route::post('/chart/swagelo', [ChartSwageloController::class, 'dataByDate'])->name('chart.swagelo.byDate');
Route::post('/chart/swagelo/direct', [ChartSwageloController::class, 'fetchDirect'])->name('chart.swagelo.direct');
Route::post('/chart/swagelo/castomMetric', [ChartSwageloController::class, 'getCastomMetric'])->name('chart.swagelo.castom');


Route::get('/hylok', [ChartHylokController::class, 'index'])->name('chart.hylok');
Route::post('/chart/hylok', [ChartHylokController::class, 'dataByDate'])->name('chart.hylok.byDate');
Route::post('/chart/hylok/direct', [ChartHylokController::class, 'fetchDirect'])->name('chart.hylok.direct');
Route::post('/chart/hylok/castomMetric', [ChartHylokController::class, 'getCastomMetric'])->name('chart.hylok.castom');


Route::get('/hy-lok', [ChartHy_LokController::class, 'index'])->name('chart.hy-lok');
Route::post('/chart/hy-lok', [ChartHy_LokController::class, 'dataByDate'])->name('chart.hy-lok.byDate');
Route::post('/chart/hy-lok/direct', [ChartHy_LokController::class, 'fetchDirect'])->name('chart.hy-lok.direct');
Route::post('/chart/hy-lok/castomMetric', [ChartHy_LokController::class, 'getCastomMetric'])->name('chart.hy-lok.castom');
