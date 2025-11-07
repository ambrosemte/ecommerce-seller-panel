<?php

use App\Http\Controllers\DeployController;
use App\Http\Middleware\CheckAuth;
use App\Livewire\Auth\Login;
use App\Livewire\Product\CreateProduct;
use App\Livewire\Store\CreateStore;
use App\Livewire\Dashboard;
use App\Livewire\Order\ListOrder;
use App\Livewire\Product\ListProduct;
use App\Livewire\Store\ListStore;
use App\Livewire\Store\ViewStore;
use App\Livewire\Product\ViewProduct;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', Login::class)->name('login')->middleware('guest');
Route::get('/deploy', [DeployController::class, 'deploy'])->name('delpoy')->middleware('guest');

Route::group(['middleware' => CheckAuth::class], function () {
    //dashboard
    Route::get('dashboard', Dashboard::class)->name('dashboard');

    //store
    Route::group(['prefix' => "store"], function () {
        Route::get('/', ListStore::class)->name('store');
        Route::get('create', CreateStore::class)->name('store.create');
        Route::get('{id}', ViewStore::class)->name('store.view');
    });

    //product
    Route::group(['prefix' => "product"], function () {
        Route::get('/', ListProduct::class)->name('product');
        Route::get('create', CreateProduct::class)->name('product.create');
        Route::get('{id}', ViewProduct::class)->name('product.view');
    });

    //order
    Route::group(['prefix' => "order"], function () {
        Route::get('/', ListOrder::class)->name('order');
    });
});
