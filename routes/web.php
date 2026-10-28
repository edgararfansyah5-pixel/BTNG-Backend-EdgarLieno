<?php

use Illuminate\Support\Facades\Route;

// Halaman utama langsung diarahkan ke dokumentasi API interaktif (Scramble)
Route::get('/', fn () => redirect('/docs/api'));
