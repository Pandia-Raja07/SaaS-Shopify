<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('admin/product/products/{product}/edit', 'ProductsController@edit')->name('admin.product.products.edit');

