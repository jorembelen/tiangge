<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Image;
use App\Category;
use App\Product;
use App\ProductsAttribute;
use Auth;
use Session;


class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::get();
        return view('admin.products.view_products', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where(['parent_id' => 0])->get();
        $categories_dropdown = "<option name='category_id' id='category_id' selected disabled>Select</option>";
        foreach($categories as $cat) {
            $categories_dropdown .= "<option value='".$cat->id."'>".$cat->name."</option>";
            $sub_categories = Category::where(['parent_id' => $cat->id])->get();
			foreach($sub_categories as $sub_cat){
				$categories_dropdown .= "<option value='".$sub_cat->id."'>&nbsp;&nbsp;--&nbsp;".$sub_cat->name."</option>";
        }
    }
        return view('admin.products.add_products', compact('categories_dropdown'));
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

        if(empty($data['category_id'])) {
            return redirect()->back()->with('flash_message_error', 'Under category cannot be null.');    
        }
        $product = new Product;
        $product->category_id = $data['category_id'];
        $product->product_name = $data['product_name'];
        $product->product_code = $data['product_code'];
        $product->product_color = $data['product_color'];
        if(!empty($data['description'])) {
            $product->description = $data['description'];
        }else{
            $product->description = '';
        }
        $product->price = $data['price'];

        // Upload Image
        if($request->hasFile('image')) {
            $image_tmp = Input::file('image');
            if($image_tmp->isValid()) {
                $extension = $image_tmp->getClientOriginalExtension();
                $filename = rand(111,99999).'.'.$extension;
                $large_image_path = 'images/backend_images/products/large/' .$filename;
                $medium_image_path = 'images/backend_images/products/medium/' .$filename;
                $small_image_path = 'images/backend_images/products/small/' .$filename;
                // Resize Images
                Image::make($image_tmp)->save($large_image_path);
                Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
                Image::make($image_tmp)->resize(300,300)->save($small_image_path);

                // Store image name in products table
                $product->image = $filename;
            }
        }
        $product->save();
        
        return redirect(route('products.index'))->with('flash_message_success', 'Product was added successfully.');
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
        $productDetails = Product::where(['id'=> $id])->first();

        // Categories Drop-down start
        $categories = Category::where(['parent_id' => 0])->get();
        $categories_dropdown = "<option name='category_id' id='category_id' selected disabled>Select</option>";
        foreach($categories as $cat) {
            if($cat->id == $productDetails->category_id) {
                $selected = "selected";
            }else{
                $selected = '';
            }
            $categories_dropdown .= "<option value='".$cat->id."' ".$selected.">".$cat->name."</option>";
            $sub_categories = Category::where(['parent_id' => $cat->id])->get();
			foreach($sub_categories as $sub_cat){
                if($sub_cat->id == $productDetails->category_id) {
                    $selected = "selected";
                }else{
                    $selected = '';
                }
				$categories_dropdown .= "<option value='".$sub_cat->id."' ".$selected.">&nbsp;&nbsp;--&nbsp;".$sub_cat->name."</option>";
        }
    }
    // End

        return view('admin.products.edit_product', compact('productDetails', 'categories_dropdown'));
    }

    public function editProduct(Request $request, $id)
    {
        if($request->isMethod('post')) {
            $data = $request->all();

             // Upload Image
        if($request->hasFile('image')) {
            $image_tmp = Input::file('image');
            if($image_tmp->isValid()) {
                $extension = $image_tmp->getClientOriginalExtension();
                $filename = rand(111,99999).'.'.$extension;
                $large_image_path = 'images/backend_images/products/large/' .$filename;
                $medium_image_path = 'images/backend_images/products/medium/' .$filename;
                $small_image_path = 'images/backend_images/products/small/' .$filename;
                // Resize Images
                Image::make($image_tmp)->save($large_image_path);
                Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
                Image::make($image_tmp)->resize(300,300)->save($small_image_path);
            }
        }else{
            $filename = $data['current_image'];
        }

        if(empty($data['description'])) {
            $data['description'] = "";
        }

            Product::where(['id' => $id])->update([
                'category_id' => $data['category_id'],
                'product_name' => $data['product_name'],
                'product_code' => $data['product_code'],
                'product_color' => $data['product_color'],
                'description' => $data['description'],
                'price' => $data['price'],
                'image' => $filename
            ]);
            return redirect(route('products.index'))->with('flash_message_success', 'Product updated successfully.');
        
        }
        
    }

    public function Attributes($id)
    {
        $productDetails = Product::with('attributes')->where(['id'=> $id])->first();

        return view('admin.products.add_attributes', compact('productDetails'));
    }

    public function AddAtrributes(Request $request, $id) 
    {
        $productDetails = Product::with('attributes')->where(['id'=> $id])->first();

        if($request->isMethod('post')) {
            $data = $request->all();
            // dd($data);
            foreach($data['sku'] as $key => $val){
                if(!empty($val)){
                    $attribute = new ProductsAttribute;
                    $attribute->product_id = $id;
                    $attribute->sku = $val;
                    $attribute->size = $data['size'][$key];
                    $attribute->price = $data['price'][$key];
                    $attribute->stock = $data['stock'][$key];
                    $attribute->save();
                }
            } 
        }
        return redirect(route('products.index'))
        ->with('flash_message_success', 'Attributes has been added successfully');
    }

    
    public function deleteProduct($id = null){

        $pImages = Product::where(['id'=>$id])->first();

        $file = $pImages->image;

        $file1 = public_path().'/images/backend_images/products/small/'.$file;
        $file2 = public_path().'/images/backend_images/products/medium/'.$file;
        $file3 = public_path().'/images/backend_images/products/large/'.$file;
        
        $filename = array($file1, $file2, $file3);
        $exists = false;
        
        foreach ($filename as $file) {
            if (file_exists($file)) {
                $exists = true;
            }
        }
        if ($exists == true) {
            foreach($filename as $file) {
                unlink($file);

        Product::where(['id'=>$id])->delete();
        ProductsAttribute:: where(['product_id' => $id])->delete();
            }  
        return redirect()->back()->with('flash_message_success', 'Product has been deleted successfully');
        }     
        
    }

    public function deleteImage($id = null){

    $pImages = Product::where(['id'=>$id])->first();

    $file = $pImages->image;

    $file1 = public_path().'/images/backend_images/products/small/'.$file;
    $file2 = public_path().'/images/backend_images/products/medium/'.$file;
    $file3 = public_path().'/images/backend_images/products/large/'.$file;
    
    $filename = array($file1, $file2, $file3);
    $exists = false;
    
    foreach ($filename as $file) {
        if (file_exists($file)) {
            $exists = true;
        }
    }
    if ($exists == true) {
        foreach($filename as $file) {
            unlink($file);
            Product::where(['id'=>$id])->update(['image' => '']);
        }  
    return redirect(route('products.index'))->with('flash_message_success','Product Image has been deleted successfully!');
        }else{
            return redirect()->back()->with('flash_message_error','Product image  could not deleted because there is no image');
        }
    }

    public function deleteAttribute($id = null) 
    {
        ProductsAttribute::where(['id'=>$id])->delete(); 

        return redirect()->back()->with('flash_message_success', 'Attribute has been deleted successfully');
    }
    
    public function products($url = null)
    {
        // Get All categories and sub-categories
        $categories = Category::with('categories')->where(['parent_id' => 0])->get();

        $categoryDetails = Category::where(['url' => $url])->first();
        // echo $categoryDetails->id; die;
        if($categoryDetails->parent_id == 0) {
        // If url is main category
            $subCategories = Category::where(['parent_id' => $categoryDetails->id])->get();
            $cat_ids = "";
            foreach($subCategories as $subcat){
                $cat_ids .= $subcat->id.",";
            }
            $productsAll = Product::whereIn('category_id',array($cat_ids))->get();
        }else{
            // If url is sub-category
            $productsAll = Product::where(['category_id' => $categoryDetails->id])->get();
        }
        
        return view('products.listing', compact('categoryDetails', 'productsAll', 'categories'));
    }
}