<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MedicalProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MedicalProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Super Admin|Product Manager']);
    }

    public function index()
    {
        $categories = Category::with('products')->get();
        return view('medical-products.index', compact('categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('medical-products.create', compact('categories'));
    }

    public function show(MedicalProduct $product)
    {
        return view('medical-products.show', compact('product'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'details' => 'required|string',
            'image' => 'nullable|image|max:10240', // Main product image
            'product_images' => 'nullable|array|max:3', // Additional product images (up to 3)
            'product_images.*' => 'image|max:10240',
            'is_featured' => 'nullable|boolean'
        ]);

        // Handle main product image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }
        
        // Handle additional product images upload (max 3)
        if ($request->hasFile('product_images')) {
            $productImages = [];
            foreach ($request->file('product_images') as $image) {
                $path = $image->store('product-images', 'public');
                $productImages[] = $path;
            }
            $validated['product_images'] = $productImages;
        }

        $validated['is_featured'] = $request->has('is_featured');

        MedicalProduct::create($validated);

        return redirect()->route('medical-products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(MedicalProduct $product)
    {
        $categories = Category::all();
        return view('medical-products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, MedicalProduct $product)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'details' => 'required|string',
            'image' => 'nullable|image|max:10240', // Main product image
            'product_images' => 'nullable|array|max:3', // Additional product images (up to 3)
            'product_images.*' => 'image|max:10240',
            'is_featured' => 'nullable|boolean'
        ]);

        // Handle main product image upload
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }
        
        // Handle additional product images upload (max 3)
        if ($request->hasFile('product_images')) {
            // Delete existing product images if there are any
            if (!empty($product->product_images) && is_array($product->product_images)) {
                foreach ($product->product_images as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }
            
            // Store new additional product images
            $productImages = [];
            foreach ($request->file('product_images') as $image) {
                $path = $image->store('product-images', 'public');
                $productImages[] = $path;
            }
            $validated['product_images'] = $productImages;
        }

        $validated['is_featured'] = $request->has('is_featured');

        $product->update($validated);

        return redirect()->route('medical-products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(MedicalProduct $product)
    {
        // Delete main product image if exists
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        
        // Delete all additional product images if there are any
        if (!empty($product->product_images) && is_array($product->product_images)) {
            foreach ($product->product_images as $productImage) {
                Storage::disk('public')->delete($productImage);
            }
        }
        
        $product->delete();

        return redirect()->route('medical-products.index')
            ->with('success', 'Product deleted successfully.');
    }
} 
