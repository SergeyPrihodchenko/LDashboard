<?php

use App\Http\Controllers\Direct\UpdateDirectController;
use Illuminate\Support\Facades\Route;

Route::post('/update/direct', [UpdateDirectController::class, 'initUpdateDirect'])->name('update.direct');