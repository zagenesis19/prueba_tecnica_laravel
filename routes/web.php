<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    // Obtenemos los productos para la vista simple de frontend
    $products = Product::select('name', 'price')->get();
    return view('products', compact('products'));
});
