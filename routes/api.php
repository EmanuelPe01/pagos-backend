<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ComprasController;
use App\Http\Controllers\PagosController;
use App\Http\Controllers\ProductoController;
use Illuminate\Support\Facades\Route;

//Rutas para el controlador del cliente
Route::get('/allClients', [ClienteController::class, 'getAll']);
Route::post('/createClient', [ClienteController::class, 'store']);
Route::get('/getClient/{id}', [ClienteController::class, 'show']);
Route::get('/getBuys/{id}', [ClienteController::class, 'showWithoutFilter']);

//Rutas para el controlador de productos
Route::post('/createProduct', [ProductoController::class, 'store']);
Route::get('/allProducts', [ProductoController::class, 'getAll']);
Route::delete('/deleteProduct/{id}', [ProductoController::class, 'destroy']);

//Rutas para el controlador de compras
Route::post('/createBuy', [ComprasController::class, 'store']);
Route::delete('/deleteBuy/{id}', [ComprasController::class, 'destroy']);
Route::put('/editBuy/{id}', [ComprasController::class, 'editBuy']);

//Rutas para el controlador de pagos
Route::post('/savePayment', [PagosController::class, 'store']);
Route::get('/getPaymentsForBuy/{id}', [PagosController::class, 'getForBuy']);
Route::put('/editPayment/{id}', [PagosController::class, 'editPayment']);
