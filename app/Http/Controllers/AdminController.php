<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Products', [
            'products' => Product::latest()->get(),
        ]);
    }

    public function show(Product $product)
    {
        return Inertia::render('Admin/ProductDetail', [
            'product' => $product->load('images'),
        ]);
    }

    public function storeImage(Request $request, Product $product)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imageSnippet = $request->file('image');
            $imageName = time() . '_' . $imageSnippet->getClientOriginalName();
            
            // Ensure directory exists
            $destinationPath = public_path('images/products');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $imageSnippet->move($destinationPath, $imageName);
            $filePath = 'images/products/' . $imageName;

            $product->images()->create([
                'file_path' => $filePath,
                'is_primary' => $product->images()->count() === 0,
            ]);
        }

        return back();
    }
}
