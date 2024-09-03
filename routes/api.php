<?php

use App\Http\Controllers\v1\AuthController;
use App\Http\Controllers\v1\OrderDocumentMailerController;
use App\Http\Controllers\v1\BoxesReportController;
use App\Http\Controllers\v1\CeboController;
use App\Http\Controllers\v1\CeboDispatchController;
use App\Http\Controllers\v1\CustomerController;
use App\Http\Controllers\v1\IncotermController;
use App\Http\Controllers\v1\OrderController;
use App\Http\Controllers\v1\PalletController;
use App\Http\Controllers\v1\PaymentTermController;
use App\Http\Controllers\v1\PDFController;
use App\Http\Controllers\v1\ProductController;
use App\Http\Controllers\v1\ProductionController;
use App\Http\Controllers\v1\RawMaterialController;
use App\Http\Controllers\v1\RawMaterialReceptionController;
use App\Http\Controllers\v1\RawMaterialReceptionsStatsController;
use App\Http\Controllers\v1\SalespersonController;
use App\Http\Controllers\v1\SpeciesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\v1\StoreController;
use App\Http\Controllers\v1\StoredPalletController;
use App\Http\Controllers\v1\StoresStatsController;
use App\Http\Controllers\v1\SupplierController;
use App\Http\Controllers\v1\TransportController;
use App\Http\Resources\v1\CustomerResource;
use App\Models\PaymentTerm;
use Illuminate\Support\Facades\App;

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

Route::post('v1/register', [AuthController::class, 'register']);
Route::post('v1/login', [AuthController::class, 'login']);
Route::post('v1/logout', [AuthController::class, 'logout']);
Route::get('v1/me', [AuthController::class, 'me'])->middleware('auth:api');


//Route::group(['middleware' => ['auth:api']], function () {

Route::apiResource('v1/stores/pallets', StoredPalletController::class)
    ->names([
        'index' => 'stores.pallets.index',
        'create' => 'stores.pallets.create',
        'store' => 'stores.pallets.store',
        'show' => 'stores.pallets.show',
        'edit' => 'stores.pallets.edit',
        'update' => 'stores.pallets.update',
        'destroy' => 'stores.pallets.destroy',
    ]);
Route::apiResource('v1/pallets', PalletController::class);
Route::apiResource('v1/stores', StoreController::class)->only(['show', 'index']);
Route::apiResource('v1/articles/products', ProductController::class)->only(['show', 'index']);
Route::apiResource('v1/customers', CustomerController::class);
Route::apiResource('v1/orders', OrderController::class);
Route::apiResource('v1/transports', TransportController::class);
Route::apiResource('v1/salespeople', SalespersonController::class);
Route::apiResource('v1/payment_terms', PaymentTermController::class);
Route::apiResource('v1/productions', ProductionController::class);
Route::apiResource('v1/suppliers', SupplierController::class);
Route::apiResource('v1/raw-material-receptions', RawMaterialReceptionController::class);
Route::apiResource('v1/cebo-dispatches', CeboDispatchController::class);
Route::apiResource('v1/species', SpeciesController::class);
Route::apiResource('v1/raw-materials', RawMaterialController::class);
Route::apiResource('v1/cebos', CeboController::class);


/* Incorterm */
Route::apiResource('v1/incoterms', IncotermController::class);
Route::get('v1/boxes_report', [BoxesReportController::class, 'exportToExcel'])->name('export.boxes');
// Ruta personalizada para enviar documentación de un pedido (NO CRUD)
Route::post('v1/send_order_documentation/{orderId}', [OrderDocumentMailerController::class, 'sendDocumentation'])->name('send_order_documentation');
/* Send order documentation to Transport  */
Route::post('v1/send_order_documentation_transport/{orderId}', [OrderDocumentMailerController::class, 'sendDocumentationTransport'])->name('send_order_documentation_transport');
Route::get('v1/orders/{orderId}/delivery-note', [PDFController::class, 'generateDeliveryNote'])->name('generate_delivery_note');
Route::get('v1/orders/{orderId}/restricted-delivery-note', [PDFController::class, 'generateRestrictedDeliveryNote'])->name('generate_restricted_delivery_note');
Route::get('v1/orders/{orderId}/order-signs', [PDFController::class, 'generateOrderSigns'])->name('generate_order_signs');
Route::get('v1/orders/{orderId}/order_CMR', [PDFController::class, 'generateOrderCMR'])->name('generate_order_CMR');
Route::get('v1/rawMaterialReceptions/document', [PDFController::class, 'generateRawMaterialReceptionsDocument'])->name('generate_raw_material_receptions_document');

/* No funciona */
/* Route::get('v1/monthly-stats', [RawMaterialReceptionsStatsController::class, 'getMonthlyStats'])->name('raw_material_receptions.monthly_stats'); */


/* No funciona */
Route::get('v1/raw-material-receptions-monthly-stats', [RawMaterialReceptionsStatsController::class, 'getMonthlyStats'])->name('raw_material_receptions.monthly_stats');
Route::get('v1/raw-material-receptions-annual-stats', [RawMaterialReceptionsStatsController::class, 'getAnnualStats'])->name('raw_material_receptions.annual_stats');
Route::get('v1/raw-material-receptions-daily-by-products-stats', [RawMaterialReceptionsStatsController::class, 'getDailyByProductsStats'])->name('raw_material_receptions.daily_by_products_stats');
/* totalInventoryBySpecies */
Route::get('v1/total-inventory-by-species', [StoresStatsController::class, 'totalInventoryBySpecies'])->name('total_inventory_by_species');

Route::get('v1/ceboDispatches/document', [PDFController::class, 'generateCeboDispatchesDocument'])->name('generate_cebo_document');


//});
