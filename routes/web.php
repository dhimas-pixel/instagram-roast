<?php

use App\Http\Controllers\InstagramController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::post('/scrape-instagram', [InstagramController::class, 'scrapeInstagramProfile'])->name('scrape-instagram');
// Route::get('/scrape-instagram/{username}', [InstagramController::class, 'scrapeInstagramProfile'])->name('scrape-instagram');

Route::get('/', [InstagramController::class, 'index'])->name('home');
