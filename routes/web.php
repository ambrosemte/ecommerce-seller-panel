<?php

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

Route::group(['middleware' => CheckAuth::class], function () {
    //dashboard
    Route::get('dashboard', Dashboard::class)->name('dashboard');

    //store
    Route::group(['prefix' => "store"], function () {
        Route::get('/', ListStore::class)->name('store');
        Route::get('create', CreateStore::class)->name('create.store');
        Route::get('{id}', ViewStore::class)->name('view.store');
    });

    //product
    Route::group(['prefix' => "product"], function () {
        Route::get('/', ListProduct::class)->name('product');
        Route::get('create', CreateProduct::class)->name('create.product');
        Route::get('{id}', ViewProduct::class)->name('view.product');
    });

    //order
    Route::group(['prefix' => "order"], function () {
        Route::get('/', ListOrder::class)->name('order');
    });
});
