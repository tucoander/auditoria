<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartonController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//Products
Route::get('/products', [ProductController::class, 'index'])
    ->middleware(['auth'])
    ->name('products');

Route::post('/products', [ProductController::class, 'store'])
    ->middleware(['auth'])
    ->name('products_store');

Route::get('/products/show', [ProductController::class, 'show'])
    ->middleware(['auth'])
    ->name('products_show');

//Cartons 
Route::get('/cartons', [CartonController::class, 'index'])
    ->middleware(['auth'])
    ->name('cartons');

Route::post('/cartons', [CartonController::class, 'store'])
    ->middleware(['auth'])
    ->name('cartons_store');


//Dashboard padrao
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__ . '/auth.php';
