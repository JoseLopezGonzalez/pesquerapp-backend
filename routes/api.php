<?php

use App\Http\Controllers\v1\AuthController;
use App\Http\Controllers\v1\OrderDocumentMailerController;
use App\Http\Controllers\v1\BoxesReportController;
use App\Http\Controllers\v1\CaptureZoneController;
use App\Http\Controllers\v1\CeboController;
use App\Http\Controllers\v1\CeboDispatchController;
use App\Http\Controllers\v1\CustomerController;
use App\Http\Controllers\v1\IncotermController;
use App\Http\Controllers\v1\OrderController;
use App\Http\Controllers\v1\PalletController;
use App\Http\Controllers\v1\PaymentTermController;
use App\Http\Controllers\v1\PDFController;
use App\Http\Controllers\v1\ProcessController;
use App\Http\Controllers\v1\ProductController;
use App\Http\Controllers\v1\ProductionController;
use App\Http\Controllers\v1\RawMaterialController;
use App\Http\Controllers\v1\RawMaterialReceptionController;
use App\Http\Controllers\v1\RawMaterialReceptionsReportController;
use App\Http\Controllers\v1\RawMaterialReceptionsStatsController;
use App\Http\Controllers\v1\SalespersonController;
use App\Http\Controllers\v1\SpeciesController;
use App\Http\Controllers\v1\ProcessNodeController;
use App\Http\Controllers\v1\FinalNodeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\v1\StoreController;
use App\Http\Controllers\v1\StoredPalletController;
use App\Http\Controllers\v1\StoresStatsController;
use App\Http\Controllers\v1\SupplierController;
use App\Http\Controllers\v1\TransportController;
use App\Http\Controllers\v2\ActivityLogController;
use App\Http\Controllers\v2\AuthController as V2AuthController;
use App\Http\Controllers\v2\CustomerController as V2CustomerController;
use App\Http\Controllers\v2\IncotermController as V2IncotermController;
use App\Http\Resources\v1\CustomerResource;
use App\Models\PaymentTerm;
use Illuminate\Support\Facades\App;

/* API V2 */
use App\Http\Controllers\v2\OrderController as V2OrderController;
use App\Http\Controllers\v2\OrdersReportController;
use App\Http\Controllers\v2\ProductController as V2ProductController;
use App\Http\Controllers\v2\RawMaterialReceptionController as V2RawMaterialReceptionController;
use App\Http\Controllers\v2\SalespersonController as V2SalespersonController;
use App\Http\Controllers\v2\SpeciesController as V2SpeciesController;
use App\Http\Controllers\v2\SupplierController as V2SupplierController;
use App\Http\Controllers\v2\TransportController as V2TransportController;
use App\Http\Controllers\v2\UserController;

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
Route::apiResource('v1/suppliers', SupplierController::class);
Route::apiResource('v1/raw-material-receptions', RawMaterialReceptionController::class);
Route::apiResource('v1/cebo-dispatches', CeboDispatchController::class);
Route::apiResource('v1/species', SpeciesController::class);
/* CaptureZones */
Route::apiResource('v1/capture_zones', CaptureZoneController::class);
Route::apiResource('v1/raw-materials', RawMaterialController::class);
Route::apiResource('v1/cebos', CeboController::class);
Route::apiResource('v1/productions', ProductionController::class);
Route::apiResource('v1/processes', ProcessController::class);





/* Incorterm */
Route::apiResource('v1/incoterms', IncotermController::class);
Route::get('v1/boxes_report', [BoxesReportController::class, 'exportToExcel'])->name('export.boxes');
/* RawMaterialReceptionsReportController */
Route::get('v1/raw_material_receptions_report', [RawMaterialReceptionsReportController::class, 'exportToExcel'])->name('export.raw_material_receptions');
// Ruta personalizada para enviar documentación de un pedido (NO CRUD)
Route::post('v1/send_order_documentation/{orderId}', [OrderDocumentMailerController::class, 'sendDocumentation'])->name('send_order_documentation');
/* Send order documentation to Transport  */
Route::post('v1/send_order_documentation_transport/{orderId}', [OrderDocumentMailerController::class, 'sendDocumentationTransport'])->name('send_order_documentation_transport');


Route::get('v1/orders/{orderId}/delivery-note', [PDFController::class, 'generateDeliveryNote'])->name('generate_delivery_note');
Route::get('v1/orders/{orderId}/restricted-delivery-note', [PDFController::class, 'generateRestrictedDeliveryNote'])->name('generate_restricted_delivery_note');
Route::get('v1/orders/{orderId}/order-signs', [PDFController::class, 'generateOrderSigns'])->name('generate_order_signs');
Route::get('v1/orders/{orderId}/order_CMR', [PDFController::class, 'generateOrderCMR'])->name('generate_order_CMR');

/* La Pesca del Meridión */
Route::get('v1/orders/{orderId}/order_CMR_pesca', [PDFController::class, 'generateOrderCMRPesca'])->name('generate_order_CMR_Pesca');
Route::get('v1/orders/{orderId}/delivery-note-pesca', [PDFController::class, 'generateDeliveryNotePesca'])->name('generate_delivery_note_pesca');
Route::get('v1/orders/{orderId}/restricted-delivery-note-pesca', [PDFController::class, 'generateRestrictedDeliveryNotePesca'])->name('generate_restricted_delivery_note_pesca');
Route::get('v1/orders/{orderId}/order-signs-pesca', [PDFController::class, 'generateOrderSignsPesca'])->name('generate_order_signs_pesca');

/* d */
Route::get('v1/rawMaterialReceptions/document', [PDFController::class, 'generateRawMaterialReceptionsDocument'])->name('generate_raw_material_receptions_document');

/* No funciona */
/* Route::get('v1/monthly-stats', [RawMaterialReceptionsStatsController::class, 'getMonthlyStats'])->name('raw_material_receptions.monthly_stats'); */


/* Process Node  */
Route::get('v1/process-nodes-decrease', [ProcessNodeController::class, 'getProcessNodesDecrease']);

/* Final node */
Route::get('v1/final-nodes-profit', [FinalNodeController::class, 'getFinalNodesProfit']);


/* No funciona */
Route::get('v1/raw-material-receptions-monthly-stats', [RawMaterialReceptionsStatsController::class, 'getMonthlyStats'])->name('raw_material_receptions.monthly_stats');
Route::get('v1/raw-material-receptions-annual-stats', [RawMaterialReceptionsStatsController::class, 'getAnnualStats'])->name('raw_material_receptions.annual_stats');
Route::get('v1/raw-material-receptions-daily-by-products-stats', [RawMaterialReceptionsStatsController::class, 'getDailyByProductsStats'])->name('raw_material_receptions.daily_by_products_stats');
/* totalInventoryBySpecies */
Route::get('v1/total-inventory-by-species', [StoresStatsController::class, 'totalInventoryBySpecies'])->name('total_inventory_by_species');
/* totalInventoryByProducts */
Route::get('v1/total-inventory-by-products', [StoresStatsController::class, 'totalInventoryByProducts'])->name('total_inventory_by_products');

Route::get('v1/ceboDispatches/document', [PDFController::class, 'generateCeboDispatchesDocument'])->name('generate_cebo_document');






/* Api V2 */
/* Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('v2/login', [V2AuthController::class, 'login'])->name('login');
    Route::post('v2/logout', [V2AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('v2/me', [V2AuthController::class, 'me'])->middleware('auth:sanctum');
    Route::apiResource('v2/orders', V2OrderController::class);
    Route::apiResource('v2/raw-material-receptions', V2RawMaterialReceptionController::class);
    Route::get('v2/orders_report', [OrdersReportController::class, 'exportToExcel'])->name('export.orders');
}); */

Route::group(['prefix' => 'v2'], function () {
    // Rutas públicas (sin autenticación)
    Route::post('login', [V2AuthController::class, 'login'])->name('v2.login');
    Route::post('logout', [V2AuthController::class, 'logout'])->middleware('auth:sanctum')->name('v2.logout');
    Route::get('me', [V2AuthController::class, 'me'])->middleware('auth:sanctum')->name('v2.me');

    // Rutas protegidas por Sanctum
    Route::middleware(['auth:sanctum'])->group(function () {
        // Rutas para Superusuario (Técnico)
        Route::middleware(['role:superuser'])->group(function () {
            Route::get('orders_report', [OrdersReportController::class, 'exportToExcel'])->name('v2.export.orders');
            Route::get('activity-log', [ActivityLogController::class, 'index'])->name('v2.activity.log');
            /* Users */
            Route::apiResource('users', UserController::class);
        });

        // Rutas para Gerencia
        Route::middleware(['role:manager'])->group(function () {
            Route::apiResource('orders', V2OrderController::class)->only(['index', 'show']);
        });

        // Rutas para Administración
        Route::middleware(['role:admin'])->group(function () {
            Route::apiResource('orders', V2OrderController::class)->except(['destroy']);
            Route::apiResource('raw-material-receptions', V2RawMaterialReceptionController::class);
        });

        // Rutas accesibles para múltiples roles
        Route::middleware(['role:superuser,manager,admin'])->group(function () {
            Route::apiResource('orders', V2OrderController::class)->only(['index', 'show']);
            Route::apiResource('raw-material-receptions', V2RawMaterialReceptionController::class);
            Route::apiResource('transports', V2TransportController::class);
            /* Products */
            Route::apiResource('products', V2ProductController::class);

            //Route::get('shared-resource', [SomeController::class, 'sharedMethod'])->name('v2.shared.resource');
            Route::get('/customers/options', [V2CustomerController::class, 'options']);
            Route::get('/salespeople/options', [V2SalespersonController::class, 'options']);
            Route::get('/transports/options', [V2TransportController::class, 'options']);
            Route::get('/incoterms/options', [V2IncotermController::class, 'options']);
            Route::get('/suppliers/options', [V2SupplierController::class, 'options']);
            Route::get('/species/options', [V2SpeciesController::class, 'options']);
            Route::get('/products/options', [V2ProductController::class, 'options']);








        });
    });
});




//});
