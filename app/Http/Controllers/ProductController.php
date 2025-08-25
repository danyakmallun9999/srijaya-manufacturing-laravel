<?php

// app/Http/Controllers/ProductController.php
namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Order; // Added this import for custom products

class ProductController extends Controller
{
    public function index()
    {
        $fixedProducts = Product::latest()->paginate(10);
        
        // Ambil produk custom dari orders
        $customProducts = Order::where('product_type', 'custom')
            ->with('customer')
            ->latest()
            ->paginate(10);
            
        return view('products.index', compact('fixedProducts', 'customProducts'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:products',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock' => 'nullable|integer|min:0',
            'model' => 'nullable|string|max:255',
            'wood_type' => 'nullable|string|max:255',
            'details' => 'nullable|string',
            'product_category' => 'nullable|in:tetap,custom',
            'bom_master' => 'nullable|json',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $validatedData['image'] = $imagePath;
        }

        // Set default values
        $validatedData['stock'] = $validatedData['stock'] ?? 0;
        $validatedData['product_category'] = $validatedData['product_category'] ?? 'tetap';

        Product::create($validatedData);

        return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:products,name,' . $product->id,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'stock' => 'nullable|integer|min:0',
            'model' => 'nullable|string|max:255',
            'wood_type' => 'nullable|string|max:255',
            'details' => 'nullable|string',
            'product_category' => 'nullable|in:tetap,custom',
            'bom_master' => 'nullable|json',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('products', 'public');
            $validatedData['image'] = $imagePath;
        }

        // Set default values
        $validatedData['product_category'] = $validatedData['product_category'] ?? 'tetap';

        $product->update($validatedData);

        return redirect()->route('products.index')->with('success', 'Data produk berhasil diubah.');
    }

    public function destroy(Product $product)
    {
        // Delete image if exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus.');
    }

    /**
     * Update stock for a product
     */
    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $product->update(['stock' => $request->stock]);

        return redirect()->route('products.index')->with('success', 'Stok produk berhasil diupdate.');
    }
}