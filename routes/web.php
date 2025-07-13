<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\StockController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardadminCntroller;
use App\Http\Controllers\Dashboardinvestor;
use App\Http\Controllers\DashboardmitraController;
use App\Http\Controllers\InvestorController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LaporanKeuanganMitra;
use App\Http\Controllers\SahamController;
use App\Http\Controllers\SewaController;
use App\Http\Controllers\SupplyNetworkController;
use App\Http\Controllers\TakeoverController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
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
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');






Route::middleware(['auth', 'role:administrator'])->prefix('dashboard/admin')->group(function () {
    Route::get('/', [DashboardadminCntroller::class,'index'])->name('dashboard.administrator');
    // CRUD User (Satu halaman untuk Create & Edit)


        Route::prefix('/user')->group(function (){
            Route::get('/', [UserController::class, 'userpage'])->name('admin.user');
            Route::get('/manage/{id?}', [UserController::class, 'manage'])->name('admin.user.manage');
            Route::post('/store', [UserController::class, 'store'])->name('admin.user.store');
            Route::put('/update/{id}', [UserController::class, 'update'])->name('admin.user.update');
            Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('admin.user.delete');
        });

        Route::prefix('/laporan-keuangan')->group(function () {
        Route::get('/', [TransaksiController::class, 'index'])->name('admin.laporan-keuangan.index');
        Route::put('/update/{id}', [TransaksiController::class, 'updateStatus'])->name('admin.laporan-keuangan.update');
    });

        // Crud Supply Network
        Route::prefix('/supply')->group(function () {
            Route::get('/', [SupplyNetworkController::class, 'index'])->name('admin.supply');
            Route::get('/manage/{id?}', [SupplyNetworkController::class, 'manage'])->name('admin.supply.manage');
            Route::post('/store', [SupplyNetworkController::class, 'store'])->name('admin.supply.store');
            Route::put('/update/{id}', [SupplyNetworkController::class, 'update'])->name('admin.supply.update');
            Route::delete('/delete/{id}', [SupplyNetworkController::class, 'destroy'])->name('admin.supply.delete');
        });

    // CRUD Shift
    Route::prefix('/shift')->group( function (){
        Route::get('/', [ShiftController::class, 'index'])->name('admin.shift');
        Route::get('/manage/{id?}', [ShiftController::class, 'manage'])->name('admin.shift.manage');
        Route::post('/store', [ShiftController::class, 'store'])->name('admin.shift.store');
        Route::put('/update/{id}', [ShiftController::class, 'update'])->name('admin.shift.update');
        Route::delete('/delete/{id}', [ShiftController::class, 'destroy'])->name('admin.shift.delete');
    });

    // Crud Product

    Route::prefix('product')->group(function (){
        Route::get('/', [ProductController::class, 'index'])->name('admin.product');
        Route::get('/manage/{id?}', [ProductController::class, 'manage'])->name
('admin.product.manage');
        // Route::post('/save',[ProductController::class,'save'])->name('admin.product.save');
        Route::post('/admin/product/store', [ProductController::class, 'store'])->name('admin.product.store');
        Route::put('/admin/product/update/{id}', [ProductController::class, 'update'])->name('admin.product.update');

        Route::delete('/delete/{id}', [ProductController::class, 'destroy'])->name('admin.product.delete');
    });



    // CRUD Investor
    Route::prefix('/investor')->group(function () {
        Route::get('/', [InvestorController::class, 'index'])->name('admin.investor');
        Route::get('/manage/{id?}', [InvestorController::class, 'manage'])->name('admin.investor.manage');
        Route::post('/store', [InvestorController::class, 'store'])->name('admin.investor.create');
        Route::put('/update/{id}', [InvestorController::class, 'update'])->name('admin.investor.update');
        Route::delete('/delete/{id}', [InvestorController::class, 'destroy'])->name('admin.investor.delete');
    });
    Route::prefix('/saham')->group(function () {
        Route::get('/', [SahamController::class, 'index'])->name('admin.saham');
        Route::get('/manage/{id?}', [SahamController::class, 'manage'])->name('admin.master-saham.manage');
        Route::post('/store', [SahamController::class, 'store'])->name('admin.master-saham.store');
        Route::put('/update/{id}', [SahamController::class, 'update'])->name('admin.master-saham.update');
        Route::delete('/delete/{id}', [SahamController::class, 'destroy'])->name('admin.master-saham.destroy');
    });
    Route::prefix('/takeover')->group(function () {
        Route::get('/', [TakeoverController::class, 'index'])->name('admin.takeover');
        Route::get('/manage/{id?}', [TakeoverController::class, 'manage'])->name('admin.takeover.manage');
        Route::post('/store', [TakeoverController::class, 'store'])->name('admin.takeover.store');
        Route::put('/update/{id}', [TakeoverController::class, 'update'])->name('admin.takeover.update');
        Route::delete('/delete/{id}', [TakeoverController::class, 'destroy'])->name('admin.takeover.destroy');
    });

    Route::prefix('/stock')->group(function () {
        Route::get('/', [StockController::class,'index'])->name('admin.stock');
        Route::get('/detail/{id}', [StockController::class, 'detail'])->name('admin.detail.stock');
        Route::get('/manage/{id?}', [StockController::class, 'manage'])->name('admin.stock.manage');
          Route::post('/store', [StockController::class, 'store'])->name('admin.stock.store');
        Route::put('/update/{id}', [StockController::class, 'update'])->name('admin.stock.update');
        Route::delete('/delete/{id}', [StockController::class, 'destroy'])->name('admin.stock.destroy');
       Route::get('/stock/{stock_id}/history', [StockController::class, 'newStockHistory'])->name('admin.stock.newStockHistory');
    });

    // Sewa

    Route::prefix('/sewa')->group(function () {
        Route::get('/', [SewaController::class,'index'])->name('admin.sewa.index');
        Route::get('/manage/{id?}',[SewaController::class,'manage'])->name('admin.sewa.manage');
         Route::post('/store', [SewaController::class, 'store'])->name('admin.sewa.store');
        Route::put('/update/{id}', [SewaController::class, 'update'])->name('admin.sewa.update');
        Route::delete('/delete/{id}', [SewaController::class, 'destroy'])->name('admin.sewa.destroy');
    });


      Route::prefix('/laporan')->group(function () {
        Route::get('/', [LaporanController::class,'index'])->name('admin.laporan.index');
         Route::get('/manage/{id?}',[LaporanController::class,'manage'])->name('admin.laporan.manage');
          Route::post('/store', [LaporanController::class, 'store'])->name('admin.laporan.store');
        Route::put('/update/{id}', [LaporanController::class, 'update'])->name('admin.laporan.update');
    });

        Route::prefix('/laporan-keuangan')->group(function () {
        Route::get('/', [TransaksiController::class, 'indexadmin'])->name('admin.laporan-keuangan.index');
        Route::put('/update/{id}', [TransaksiController::class, 'updateStatus'])->name('admin.laporan-keuangan.update');
    });
});

Route::middleware(['auth', 'role:mitra'])->prefix('/dashboard/mitra')->group(function () {
    Route::get('/', [DashboardmitraController::class,'index'])->name('dashboard.mitra');

    Route::get('/transaksi', [TransaksiController::class,'index'])->name('dashboard.mitra.transaksi');
    Route::get('/transaksi/history', [TransaksiController::class,'Omset'])->name('omset.index');
    Route::post('/stok/{product}', [TransaksiController::class, 'store'])->name('mitra.stok.store');
    Route::post('/simpan-omset', [TransaksiController::class, 'submitOmset'])->name('mitra.simpanOmset');

    Route::prefix('/laporan')->group(function () {
        Route::get('/', [LaporanKeuanganMitra::class,'index'])->name('mitra.laporan.index');
        Route::get('/manage/{id?}', [LaporanKeuanganMitra::class,'manage'])->name('mitra.laporan.manage');
        Route::post('/store', [LaporanKeuanganMitra::class, 'store'])->name('dashboard.mitra.laporan.store');
        Route::put('/update/{id}', [LaporanKeuanganMitra::class, 'update'])->name('dashboard.mitra.laporan.update');
    });
});


Route::middleware(['auth', 'role:investor'])->group(function () {
        Route::get('/dashboard/investor', [Dashboardinvestor::class, 'index'])->name('dashboard.investor');
        Route::get('/dashboard/investor/detail-saham', [Dashboardinvestor::class, 'detail'])->name('dashboard.investor.detail-saham');
        Route::get('/dashboard/investor/beli-saham', [Dashboardinvestor::class, 'beli'])->name('dashboard.investor.beli-saham');
});
