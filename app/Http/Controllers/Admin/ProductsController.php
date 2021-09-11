<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $products = Product::join('categories', 'categories.id', '=', 'products.category_id')
            ->select([
                'products.*',
                'categories.name as category_name',
            ])
            ->paginate(10);


        return view('admin.products.index', [
            'products' => $products,
        ]);
    }


    public function create()
    {
        Gate::authorize('products.create');
        $categories = Category::all();
        return view('admin.products.create',[
            'categories'=>$categories,
            'product'=>new Product(),
        ]);
    }


    public function store(Request $request)
    {
        $request->validate(Product::validateRules());
        $request->merge([
            'slug' => Str::slug($request->post('name')),
        ]);
        $product = Product::create($request->all());
        return redirect()->route('products.index')
            ->with('success',"Product ($product->name) created");
    }


    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.show',[
            'product' => $product,
    ]);
    }


    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.products.edit', [
            'product' => $product,
            'categories' => Category::withoutTrashed(),
        ]);
    }


    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate(Product::validateRules());

        if($request->hasFile('image'))
        {
            $file=$request->file('image');
            $image_path = $file->store('/', [
                'disk' => 'uploads',
            ]);
            $request->merge([
                'image_path' => $image_path,
            ]);
        }


        $product->update( $request->all() );

        return redirect()->route('products.index')
            ->with('success', "Product ($product->name) updated.");
    }


    public function destroy($id)
    {
        Gate::authorize('products.delete');

        $product = Product::findOrFail($id);
        $product->delete();
       // Storage::disk('uploads')->delete($product->image_path);
        return redirect()->route('products.index')
            ->with('success', "Product ($product->name) deleted.");
    }

    public function trash()
    {
        $products = Product::withoutGlobalScope('active')->onlyTrashed()->paginate();
        return view('admin.products.trash', [
            'products' => $products,
        ]);
    }

    public function restore(Request $request, $id=null)
    {
        if ($id) {
            $product = Product::withoutGlobalScope('active')->onlyTrashed()->findOrFail($id);
            $product->restore();

            return redirect()->route('products.index')
                ->with('success', "Product ($product->name) restored.");
        }

        Product::withoutGlobalScope('active')->onlyTrashed()->restore();
        return redirect()->route('products.index')
            ->with('success', "All trashed products restored.");
    }

    public function forceDelete($id = null)
    {
        if ($id) {
            $product = Product::withoutGlobalScope('active')->onlyTrashed()->findOrFail($id);
            $product->forceDelete();

            return redirect()->route('products.index')
                ->with('success', "Product ($product->name) deleted forever.");
        }

        Product::withoutGlobalScope('active')->onlyTrashed()->forceDelete();
        return redirect()->route('products.index')
            ->with('success', "All trashed products deleted forever.");
    }
}
