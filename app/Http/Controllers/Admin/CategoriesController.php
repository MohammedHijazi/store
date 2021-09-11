<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;
use Traversable;

use Illuminate\Support\Str;

class CategoriesController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }

    public function index()
    {

        /*
        SELECT categories.*, parents.name as parent_name FROM
        categories LEFT JOIN categories as parents
        ON parents.id = categories.parent_id
        WHERE ststus = 'active'
        ORDER BY created_at DESC, name ASC
        */
        // return collection of Category model object
        $entries = Category::leftJoin('categories as parents', 'parents.id', '=', 'categories.parent_id')
            ->select([
                'categories.*',
                'parents.name as parent_name'
            ])
            //->where('categories.status', '=', 'active')
            ->orderBy('categories.created_at', 'DESC')
            ->orderBy('categories.name', 'ASC')
            ->withTrashed()
            ->get();


        return view('admin.categories.index', [
            'categories' => $entries,
            'title' => 'Categories List',
        ]);
    }


    public function create()
    {
        $category =new Category();
        $parents = Category::all();
        return view('admin.categories.create', compact('parents','category'));
    }

    public function store(CategoryRequest $request)
    {
        $rules = [
            'name' => 'required|string|max:255|min:3|unique:categories',
            'parent_id' => 'required|int|exists:categories,id',
            'description' => 'nullable|min:5',
            'status' => 'required|in:active,draft',
            'image' => 'image|max:512000|dimensions:min_width=300,min_height=300',
        ];
       // $clean = $request->validate($rules);

        // Request Merge
        $request->merge([
            'slug' => Str::slug($request->post('name')),
            'status' => 'active',
        ]);


        //Mass assignment
        $category = Category::create($request->all());

        return redirect()->route('categories.index');
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $category=Category::find($id);
        $parents = Category::where('id', '<>', $category->id)->get();
        return view('admin.categories.edit',compact('category','parents'));
    }


    public function update(Request $request, $id)
    {
        $category=Category::find($id);
        $category->update($request->all());

        return redirect()->route('categories.index');
    }


    public function destroy($id)
    {
        Category::destroy($id);
        return redirect()->route('categories.index')->with('success', 'Category deleted');
    }
}
