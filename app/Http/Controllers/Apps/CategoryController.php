<?php

namespace App\Http\Controllers\Apps;

use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::when(request()->q, function($categories){
            $categories = $categories->where('name', 'like', '%'.request()->q.'%');
        })->latest()->paginate(5);

        //return inertia
        return Inertia::render('Apps/Categories/Index',
        [
            'categories' => $categories
        ]);
    }

    public function create()
    {
        return Inertia::render('Apps/Categories/Create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'image' => 'required|image|max:2000',
            'name' => 'required|unique:categories',
            'description' => 'required'
        ], [
            'image.required' => 'Image harus dilampirkan',
            'image.image' => 'File yang dilampirkan harus dalam bentuk gambar',
            'image.max' => 'Ukuran maksimal file 2MB',
            'name.required' => 'Nama Kategori harus diisi',
            'name.unique' => 'Nama Kategori harus unik',
            'description.required' => 'Deskripsi kategori harus diisi'
        ]);

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/categories', $image->hashName());

        //create category
        Category::create([
            'image' => $image->hashName(),
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->route('apps.categories.index');
    }

    public function edit(Category $category)
    {
        return Inertia::render('Apps/Categories/Edit', [
            'category' => $category
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $this->validate($request, [
            'image' => 'nullable|image|max:2000',
            'name' => 'required|unique:categories,name,'.$category->id,
            'description' => 'required'
        ], [
            'image.image' => 'File yang dilampirkan harus dalam bentuk gambar',
            'image.max' => 'Ukuran maksimal file 2MB',
            'name.required' => 'Nama Kategori harus diisi',
            'name.unique' => 'Nama Kategori harus unik',
            'description.required' => 'Deskripsi kategori harus diisi'
        ]);

        //upload image
        $image = $request->file('image');
        if($image){
            Storage::disk('local')->delete('public/categories/'.basename($category->id));
            $image->storeAs('public/categories', $image->hashName());

            $category->update([
                'image' => $image->hashName(),
                'name' => $request->name,
                'description' => $request->description
            ]);
        }

        $category->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->route('apps.categories.index');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);

        Storage::disk('local')->delete('public/categories/'.basename($category->id));

        $category->delete();

        return redirect()->route('apps.categories.index');
    }
}
