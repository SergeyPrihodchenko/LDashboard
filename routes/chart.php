<?php

use App\Http\Controllers\Chart\ChartWikaController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ChartWikaController::class, 'indexWika']);
Route::get('/direct', [ChartWikaController::class, 'indexDirect']);
