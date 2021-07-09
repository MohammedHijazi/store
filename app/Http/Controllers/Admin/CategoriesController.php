<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;
use Traversable;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{

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
            ->get();

        // return collection of stdObj object
        /*$entries = DB::table('categories')
            ->where('status', '=', 'active')
            ->orderBy('created_at', 'DESC')
            ->orderBy('name', 'ASC')
            ->get();*/


        /*$categories = [];
        if ($categories instanceof Traversable) {
            echo 'Yes';
            return;
        }*/

        return view('admin.categories.index', [
            'categories' => $entries,
            'title' => 'Categories List',
        ]);
    }


    public function create()
    {
        $parents = Category::all();
        return view('admin.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        // Request Merge
        $request->merge([
            'slug' => Str::slug($request->post('name')),
            'status' => 'active',
        ]);

        // return array of all form fields
        // $request->all();
        // dd($request->all());

        // // return single field value
        // $request->description;
        // $request->input('description');
        // $request->get('description');
        // $request->post('description');
        // $request->query('description'); // ?description=value

        // Method #1
        // $category = new Category();
        // $category->name = $request->post('name');
        // $category->slug = Str::slug($request->post('name'));
        // $category->parent_id = $request->post('parent_id');
        // $category->description = $request->post('description');
        // $category->status = $request->post('status', 'active');
        // $category->save();

        // Method #2: Mass assignment
        $category = Category::create($request->all());

        // Method #3: Mass assignment
        // $category = new Category([
        //     'name' => $request->post('name'),
        //     'slug' => Str::slug($request->post('name')),
        //     'parent_id' => $request->post('parent_id'),
        //     'description' => $request->post('description'),
        //     'status' => $request->post('status', 'active'),
        // ]);
        //$category->save();

        return redirect()->route('categories.index');
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }
}
