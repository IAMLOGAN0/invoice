<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        // Auto-generate HSN code
        $validated['hsn_code'] = $this->generateHsnCode();
        
        // Set default GST percentage to 0
        $validated['gst_percentage'] = 0;

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Product created successfully');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        // Keep the existing HSN code and GST percentage
        $validated['hsn_code'] = $product->hsn_code;
        $validated['gst_percentage'] = $product->gst_percentage;

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }

    /**
     * Generate a unique HSN code
     * HSN codes are 8-digit numbers
     */
    private function generateHsnCode()
    {
        do {
            $code = str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
        } while (Product::where('hsn_code', $code)->exists());

        return $code;
    }
}
