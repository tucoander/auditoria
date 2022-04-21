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

Route::get('/', [CartonController::class, 'uploadForm'])
  ->middleware(['auth'])
  ->name('audit');

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

Route::get('/cartons/show', [CartonController::class, 'show'])
  ->middleware(['auth'])
  ->name('cartons_show');

//Auditoria
Route::get('/audit', [CartonController::class, 'uploadForm'])
  ->middleware(['auth'])
  ->name('audit');

Route::post('/audit', [CartonController::class, 'upload'])
  ->middleware(['auth'])
  ->name('cartons_store');

Route::get('/audit/list', [CartonController::class, 'listCartons'])
  ->middleware(['auth'])
  ->name('list_cartons');

Route::get('/audit/show/{id}', [CartonController::class, 'showCarton'])
  ->middleware(['auth'])
  ->name('show_carton');

Route::post('/audit/item', [CartonController::class, 'auditItemAddQuantity'])
  ->middleware(['auth'])
  ->name('audit_item');

Route::post('/audit/item/close', [CartonController::class, 'closeAuditItem'])
  ->middleware(['auth'])
  ->name('close_audit_item');

Route::post('/audit/carton/close', [CartonController::class, 'closeAuditCarton'])
  ->middleware(['auth'])
  ->name('close_audit_carton');

Route::post('/audit/carton/info', [CartonController::class, 'addInfoCarton'])
  ->middleware(['auth'])
  ->name('add_info_carton');

Route::post('/audit/carton/exceed', [CartonController::class, 'addExceedItem'])
  ->middleware(['auth'])
  ->name('add_info_carton');

//Dashboard padrao
Route::get('/dashboard', function () {
  return view('dashboard');
})
  ->middleware(['auth'])
  ->name('dashboard');

require __DIR__ . '/auth.php';
