<?php

use App\Http\Controllers\v1\OrderDocumentMailerController;
use App\Http\Controllers\v1\BoxesReportController;
use App\Http\Controllers\v1\CustomerController;
use App\Http\Controllers\v1\LocatePalletController;
use App\Http\Controllers\v1\OrderController;
use App\Http\Controllers\v1\PalletController;
use App\Http\Controllers\v1\PaymentTermController;
use App\Http\Controllers\v1\ProductController;
use App\Http\Controllers\v1\SalespersonController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\v1\StoreController;
use App\Http\Controllers\v1\StoredPalletController;
use App\Http\Controllers\v1\TransportController;
use App\Http\Resources\v1\CustomerResource;
use App\Models\PaymentTerm;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
}); */


/* Route::middleware(['cors'])->group(function () {
    Route::apiResource('v1/stores/pallets', StoredPalletController::class);
    Route::apiResource('v1/stores', StoreController::class)->only(['show' , 'index']);
    Route::apiResource('v1/articles/products', ProductController::class)->only(['show' , 'index']);
}); */

Route::apiResource('v1/stores/pallets', StoredPalletController::class);
Route::apiResource('v1/pallets', PalletController::class);
Route::apiResource('v1/stores', StoreController::class)->only(['show', 'index']);
Route::apiResource('v1/articles/products', ProductController::class)->only(['show', 'index']);
Route::apiResource('v1/customers', CustomerController::class); 
Route::apiResource('v1/orders', OrderController::class); 
Route::apiResource('v1/transports', TransportController::class);
Route::apiResource('v1/salespeople', SalespersonController::class);
Route::apiResource('v1/payment_terms' , PaymentTermController::class);
Route::apiResource('v1/boxes_report' , BoxesReportController::class)->only(['index']);
 

// Ruta personalizada para enviar documentación de un pedido (NO CRUD)
Route::post('v1/send_order_documentation/{orderId}', [OrderDocumentMailerController::class, 'sendDocumentation'])->name('send_order_documentation');