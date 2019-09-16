<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Category;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $productsAll = Product::get();

        $productsAll = Product::orderBy('id', 'DESC')->get();

        $productsAll = Product::inRandomOrder()->get();

        $categories = Category::with('categories')->where(['parent_id' => 0])->get();
        // $categories = json_decode(json_encode($categories));
        // echo "<pre>"; print_r($categories); die;

    //     $categories_menu = "";
    //     foreach($categories as $cat) {
    //         $categories_menu .="<div class='panel-heading'>
    //                 <h4 class='panel-title'>
    //                     <a data-toggle='collapse' data-parent='#accordian' href='#".$cat->id."'>
    //                         <span class='badge pull-right'><i class='fa fa-plus'></i></span>
    //                         ".$cat->name."
    //                     </a>
    //                 </h4>
    //             </div>
    //             <div id='".$cat->id."' class='panel-collapse collapse'>
    //             <div class='panel-body'>
    //                 <ul>";
    //                 $sub_categories = Category::where(['parent_id' => $cat->id])->get();
    //                 foreach($sub_categories as $subcat) {
    //                 $categories_menu .="<li><a href='#'>".$subcat->name." </a></li>";
    //                 }
    //                     $categories_menu .="</ul>
    //             </div>
    //         </div>
    //             ";
    // }
        return view('index', compact('productsAll', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
