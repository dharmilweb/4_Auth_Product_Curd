<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $Products = Product::orderBy('products.id','DESC')->paginate(2);
        return view('1_products.list',compact('Products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('1_products.view');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required',
            'detail' => 'required',
            'image' => 'required|mimes:jpg,jpeg,png|max:2048',
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->detail = $request->detail;
        
        $fieldname = 'image';
        if($request->hasFile($fieldname)) {
            $image = $request->file('image');
            $directory = 'image/';
            $six_digit_random_number = random_int(100000, 999999);
            $profileImage = date('YmdHis') ."_Product_". $six_digit_random_number . "." . $image->getClientOriginalExtension();
            $image->move($directory, $profileImage);
            $product->image = $profileImage;
        }

        $product->save();
     
        return redirect()->route('products.index')
                        ->with('success','Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
        $Products = $product->where('id',$product->id)->first();
        return view('1_products.view',compact('Products'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
        $Products = $product->where('id',$product->id)->first();
        return view('1_products.edit',compact('Products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
        if(empty($request->name) && empty($request->image)){
            $request->validate([
                'name' => 'required',
                'image' => 'nullable|mimes:jpg,jpeg,png|max:2048',
            ]);
        }
        
        $product = $product->where('id',$product->id)->first();
        $product->name = $request->name;
        $product->detail = $request->detail;
        
        $fieldname = 'image';
        if($request->hasFile($fieldname)) {
            $image = $request->file('image');
            $directory = 'image/';
            $six_digit_random_number = random_int(100000, 999999);
            $profileImage = date('YmdHis') ."_Product_". $six_digit_random_number . "." . $image->getClientOriginalExtension();
            $image->move($directory, $profileImage);
            $product->image = $profileImage;
        }

        $product->update();

        return redirect()->route('products.index')
                        ->with('success','Product updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
        $product = $product->where('id',$product->id)->first();
        unlink('image/'. $product->image);
        $product->delete();

        return response()->json([
            'success' => 'Product deleted successfully!'
        ]);

    }
}
