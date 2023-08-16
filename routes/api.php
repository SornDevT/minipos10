<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\TransectionController;
use App\Http\Controllers\BillsController;
use App\Http\Controllers\ReportController;

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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post("register",[UserController::class,"register"]);
Route::post("login",[UserController::class,"login"]);
Route::post("logout",[UserController::class,"logout"]);

Route::controller(StoreController::class)->group(function (){
    Route::get("store","index");
    Route::get("store/edit/{id}","edit");
    Route::post("store/add","add");
    Route::post("store/update/{id}","update");
    Route::delete("store/delete/{id}","delete");
});


Route::controller(TransectionController::class)->group(function (){
    Route::post("transection","index");
    // Route::get("store/edit/{id}","edit");
    Route::post("transection/add","add");
    // Route::post("store/update/{id}","update");
    // Route::delete("store/delete/{id}","delete");
});

Route::controller(BillsController::class)->group(function (){
     Route::get("bills/print/{id}","print");
});

Route::controller(ReportController::class)->group(function (){
    Route::post("report","created_report");
    Route::get("report/grapboard","grapboard");
});
