<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::get();

        return view('admin.categories.view_categories', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $levels = Category::where(['parent_id' => 0])->get();
        return view('admin.categories.add_category', compact('levels'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $category = new Category;
        $category->name = $data['category_name'];
        $category->parent_id = $data['parent_id'];
        $category->description = $data['description'];
        $category->url = $data['url'];
        $category->save();
        

        return redirect(route('categories.view'))->with('flash_message_success', 'Category was added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    public function editCategory(Request $request, $id)
    {
        if($request->isMethod('post')) {
            $data = $request->all();
            // dd($data);
            Category::where(['id' => $id])->update([
                'name' => $data['category_name'],
                'parent_id' => $data['parent_id'],
                'description' => $data['description'],
                'url' => $data['url']
            ]);
            return redirect(route('categories.view'))->with('flash_message_success', 'Category updated successfully.');
        }
        $levels = Category::where(['parent_id' => 0])->get();
        $categoryDetails = Category::where(['id'=> $id])->first();

        return view('admin.categories.edit_category', compact('categoryDetails', 'levels'));
    }


    public function deleteCategory($id = null){
        Category::where(['id'=>$id])->delete();
        return redirect()->back()->with('flash_message_success', 'Category has been deleted successfully');
    }

}
