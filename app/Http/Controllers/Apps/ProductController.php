<?php

namespace App\Http\Controllers\Apps;

use Inertia\Inertia;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::when(request()->q, function($products){
            $products = $products->where('name', 'like', '%'.request()->q.'%');
        })->latest()->paginate(5);

        //return inertia
        return Inertia::render('Apps/Products/Index',
        [
            'products' => $products
        ]);
    }

    public function create()
    {
        $categories = Category::all();
        return Inertia::render('Apps/Products/Create', [
            'categories' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|image|max:2000',
            'barcode' => 'required|unique:products',
            'description' => 'required',
            'title' => 'required',
            'category_id' => 'required',
            'buy_price' => 'required',
            'sell_price' => 'required',
            'stock' => 'required'
        ], [
            'image.required' => 'Image harus dilampirkan',
            'image.image' => 'File yang dilampirkan harus dalam bentuk gambar',
            'image.max' => 'Ukuran maksimal file 2MB',
            'title.required' => 'Nama Produk harus diisi',
            'description.required' => 'Deskripsi kategori harus diisi'
        ]);

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/products', $image->hashName());

        //create product
        Product::create([
            'image' => $image->hashName(),
            'barcode' => $request->barcode,
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'buy_price' => $request->buy_price,
            'sell_price' => $request->sell_price,
            'stock' => $request->stock,
        ]);

        return redirect()->route('apps.products.index');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return Inertia::render('Apps/Products/Edit', [
            'product' => $product,
            'categories' => $categories
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $this->validate($request, [
            'image' => 'nullable|image|max:2000',
            'barcode' => 'required|unique:products,barcode,'.$product->id,
            'description' => 'required',
            'title' => 'required',
            'category_id' => 'required',
            'buy_price' => 'required',
            'sell_price' => 'required',
            'stock' => 'required'
        ], [
            'image.required' => 'Image harus dilampirkan',
            'image.image' => 'File yang dilampirkan harus dalam bentuk gambar',
            'image.max' => 'Ukuran maksimal file 2MB',
            'title.required' => 'Nama Produk harus diisi',
            'description.required' => 'Deskripsi kategori harus diisi'
        ]);

        //upload image
        $image = $request->file('image');
        if($image){
            Storage::disk('local')->delete('public/products/'.basename($product->id));
            $image->storeAs('public/products', $image->hashName());

            $product->update([
                'image' => $image->hashName(),
                'barcode' => $request->barcode,
                'title' => $request->title,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'buy_price' => $request->buy_price,
                'sell_price' => $request->sell_price,
                'stock' => $request->stock,
            ]);
        }

        $product->update([
            'barcode' => $request->barcode,
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'buy_price' => $request->buy_price,
            'sell_price' => $request->sell_price,
            'stock' => $request->stock,
        ]);

        return redirect()->route('apps.products.index');
    }

    public function destroy($id)
    {
        $product = product::findOrFail($id);

        Storage::disk('local')->delete('public/products/'.basename($product->id));

        $product->delete();

        return redirect()->route('apps.products.index');
    }
}
