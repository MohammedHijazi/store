<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /*Category::create([
            'name'=>'model',
            'slug'=>'model-category',
            'status'=>'draft',
        ]);
    */

        /*
        for ($i = 1; $i <= 10; $i++) {
            DB::table('categories')->insert([
                'name' => 'Category ' . $i,
                'slug' => 'cateogry-' . $i,
                'status' => 'active',
            ]);
        }
        */
    /*
        DB::connection('mysql')->table('categories')->insert([
            'name'=>'My First Category',
            'slug'=>'my-first-category',
            'status'=>'active',

    ]);
        DB::connection('mysql')->table('categories')->insert([
            'name'=>'My sub Category',
            'slug'=>'my-sub-category',
            'parent_id'=>1,
            'status'=>'active',


        ]);
    */
    }
}
