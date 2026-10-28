<?php

use App\Http\Controllers\MobilController;
use Illuminate\Support\Facades\Route;

// GET    /api/mobil          -> index
// POST   /api/mobil          -> store
// GET    /api/mobil/{mobil}  -> show
// PUT    /api/mobil/{mobil}  -> update
// PATCH  /api/mobil/{mobil}  -> update
// DELETE /api/mobil/{mobil}  -> destroy
Route::apiResource('mobil', MobilController::class);
