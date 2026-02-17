<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

class ProductController extends Controller
{
    public function index()
    {
        return Inertia::render('Welcome', [
            'products' => Product::with('images')->latest()->get(),
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    }

    public function show(Product $product)
    {
        return Inertia::render('ProductDetail', [
            'product' => $product->load('images'),
        ]);
    }
}
