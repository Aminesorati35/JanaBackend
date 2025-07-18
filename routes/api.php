<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\Menu_itemController;
use App\Http\Controllers\Client\Menu_itemController as ClientMenu_itemController;
use App\Http\Controllers\ReservationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');*/

///For admin
            ///auth
Route::prefix('admin')->group(function () {
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/logout', [AdminAuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/me', [AdminAuthController::class, 'me'])->middleware(['auth:sanctum','admin.auth']);
});


Route::middleware(["auth:sanctum","admin.auth"])->group(function (){
    Route::apiResource('/menu_item',Menu_itemController::class);
    Route::get('getMenuByCategory',[Menu_itemController::class, 'getMenuByCategory']);
    Route::get('/reservations',[ReservationController::class, 'index']);
    Route::delete('/deleteReservation/{id}',[ReservationController::class, 'destroy']);
    Route::put('/changeReservationStatus/{id}',[ReservationController::class, "changeReservationStatus"]);
    Route::get('/AdminDashboard',[AdminDashboardController::class,'index']);

});


///For Clients
Route::get('menu_item_clients',[ClientMenu_itemController::class,'index']);
Route::get('getMenuByCategory_clients',[ClientMenu_itemController::class,'getMenuByCategory']);
            //reservations
Route::post('/reserve',[ReservationController::class,'store']);
Route::put('cancelReservation',[ReservationController::class, 'cancelReservation']);
Route::get('myReservation/{id}',[ReservationController::class, "show"]);
