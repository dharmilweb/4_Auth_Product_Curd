<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    //
    /**
    * @OA\Post(
    *    path="/api/product/list",
    *    tags={"Products"},
    *    summary="Product List Api",
        *    operationId="product",
        *    @OA\Response(
        *        response=200,
        *        description="Success",
        *        @OA\MediaType(
        *            mediaType="application/json",
        *        )
        *    ),
        *    @OA\Response(
        *        response=401,
        *        description="Unauthorized"
        *    ),
        *    @OA\Response(
        *        response=400,
        *        description="Invalid request"
        *    ),
        *    @OA\Response(
        *        response=403,
        *        description="Unauthorized Access"
        *    ),
        *    @OA\Response(
        *        response=404,
        *        description="not found"
        *    ),
        *   security={{ "apiAuth": {} }}
        *)
    */
    public function list(){

        $products = Product::orderBy('id','DESC')->paginate(10);
        return response()->json(['message' => '', 'list' => $products]);
    }

    /**
    * @OA\Post(
    *    path="/api/product/new",
    *    tags={"Products"},
     *    summary=" new Products",
     *    operationId="new_product",
     *    @OA\Parameter(
     *        name="name",
     *        in="query",
     *        required=true,
     *        @OA\Schema(
     *            type="string",
     *        )
     *    ),
     *    @OA\Parameter(
     *        name="detail",
     *        in="query",
     *        required=false,
     *        @OA\Schema(
     *            type="string",
     *        )
     *    ),
     *    @OA\Parameter(
     *        name="is_active",
     *        in="query",
     *        required=true,
     *        @OA\Schema(
     *           type="integer",
     * 			 enum={0,1}
     *        )
     *    ),
     *    @OA\RequestBody(
     *       required=false,
     *       @OA\MediaType(
     *       mediaType="multipart/form-data",
     *           @OA\Schema(
     *               @OA\Property(
     *                   description="file to upload", property="image",type="file",format="file",
     *               ),
     *           )
     *       )
     *    ),
     *    @OA\Response(
     *        response=200,
     *        description="Success",
     *        @OA\MediaType(
     *            mediaType="application/json",
     *        )
     *    ),
     *    @OA\Response(
     *        response=401,
     *        description="Unauthorized"
     *    ),
     *    @OA\Response(
     *        response=400,
     *        description="Invalid request"
     *    ),
     *    @OA\Response(
     *        response=403,
     *        description="Unauthorized Access"
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="not found"
     *    ),
     *   security={{ "apiAuth": {} }}
    *)
    */
    public function new(Request $request)
    {
        $attributes = $this->attributes($request);
        $fieldname = 'image';
        if($request->hasFile($fieldname)) {
            $image = $request->file('image');
            $directory = 'image/';
            $six_digit_random_number = random_int(100000, 999999);
            $profileImage = date('YmdHis') ."_Product_". $six_digit_random_number . "." . $image->getClientOriginalExtension();
            $image->move($directory, $profileImage);
            $attributes['image'] = $profileImage;
        }

        $products = Product::create($attributes);
        return response()->json(['Success' => 'New Product Created Successfully', 'list' => $products]);
    }

    /**
    * This Method use for manage requested atatribute for Create and Update
    */
    private function attributes($request)
    {
        $request = (object) $request;
        $attributes['name'] = $request->name;
        $attributes['detail'] = @$request->detail;
        $attributes['is_active'] = $request->is_active;
        return $attributes;
    }

    /**
    * @OA\Post(
    *    path="/api/product/edit/{id}",
    *    tags={"Products"},
     *    summary="update Products",
     *    operationId="update_product",
     *    @OA\Parameter(
     *        name="id",
     *        in="path",
     *        required=false,
     *        @OA\Schema(
     *           type="integer",
     *           format="int64"
     *        )
     *    ),
     *    @OA\Parameter(
     *        name="name",
     *        in="query",
     *        required=true,
     *        @OA\Schema(
     *            type="string",
     *        )
     *    ),
     *    @OA\Parameter(
     *        name="detail",
     *        in="query",
     *        required=false,
     *        @OA\Schema(
     *            type="string",
     *        )
     *    ),
     *    @OA\RequestBody(
     *       required=false,
     *       @OA\MediaType(
     *       mediaType="multipart/form-data",
     *           @OA\Schema(
     *               @OA\Property(
     *                   description="file to upload", property="image",type="file",format="file",
     *               ),
     *           )
     *       )
     *    ),
     *    @OA\Parameter(
     *        name="is_active",
     *        in="query",
     *        required=true,
     *        @OA\Schema(
     *           type="integer",
     * 			 enum={0,1}
     *        )
     *    ),
     *    @OA\Response(
     *        response=200,
     *        description="Success",
     *        @OA\MediaType(
     *            mediaType="application/json",
     *        )
     *    ),
     *    @OA\Response(
     *        response=401,
     *        description="Unauthorized"
     *    ),
     *    @OA\Response(
     *        response=400,
     *        description="Invalid request"
     *    ),
     *    @OA\Response(
     *        response=403,
     *        description="Unauthorized Access"
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="not found"
     *    ),
     *   security={{ "apiAuth": {} }}
    *)
    */
    public function update(Request $request,$id)
    {
        $checkProduct = Product::where('id', $id)->first();
        if(!$checkProduct){
            return response()->json(['Error' => 'Product not Found...']);
        } else {

            $params = $request->all();
            $attributes = $this->attributes($request->all());
            
            $fieldname = 'image';
            if($request->hasFile($fieldname)) {

                // Old Path Image Remove Inside Storage...
                $path = 'image/'.$checkProduct->image;
                unlink($path);

                // Add new Image in Storage...
                $directory = 'image/';
                $image = $request->file('image');

                $six_digit_random_number = random_int(100000, 999999);
                $profileImage = date('YmdHis') ."_Product_". $six_digit_random_number . "." . $image->getClientOriginalExtension();

                $image->move($directory, $profileImage);
                $attributes['image'] = $profileImage;
            }

            $products = Product::where('id', $id)->update($attributes);
            return response()->json(['Success' => 'Product Updated Successfully', 'list' => $attributes]);
        }

    }

    /**
    * @OA\Delete(
    *    path="/api/product/destroy/{id}",
    *    tags={"Products"},
     *    summary="delete Products",
     *    operationId="destroy_product",
     *    @OA\Parameter(
     *        name="id",
     *        in="path",
     *        required=true,
     *        @OA\Schema(
     *           type="integer",
     *           format="int64"
     *        )
     *    ),
     *    @OA\Response(
     *        response=200,
     *        description="Success",
     *        @OA\MediaType(
     *            mediaType="application/json",
     *        )
     *    ),
     *    @OA\Response(
     *        response=401,
     *        description="Unauthorized"
     *    ),
     *    @OA\Response(
     *        response=400,
     *        description="Invalid request"
     *    ),
     *    @OA\Response(
     *        response=403,
     *        description="Unauthorized Access"
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="not found"
     *    ),
     *   security={{ "apiAuth": {} }}
    *)
    */
    public function destroy(Request $request,$id)
    {
        $checkProduct = Product::where('id', $id)->first();
        if(!$checkProduct){
            return response()->json(['Error' => 'Product not Found...']);
        } else {

            if(isset($checkProduct->image) && !empty($checkProduct->image)){

                // Table Path Image Remove Inside Storage...
                $path = 'image/'.$checkProduct->image;
                unlink($path);
            }

            Product::where('id', $id)->delete();
            return response()->json(['Success' => 'Product Deleted Successfully']);
        }

    }

    /**
    * @OA\Patch(
    *    path="/api/product/activate/{id}",
    *    tags={"Products"},
     *    summary="change Products status",
     *    operationId="activate_product",
     *    @OA\Parameter(
     *        name="id",
     *        in="path",
     *        required=true,
     *        @OA\Schema(
     *           type="integer",
     *           format="int64"
     *        )
     *    ),
     *    @OA\Response(
     *        response=200,
     *        description="Success",
     *        @OA\MediaType(
     *            mediaType="application/json",
     *        )
     *    ),
     *    @OA\Response(
     *        response=401,
     *        description="Unauthorized"
     *    ),
     *    @OA\Response(
     *        response=400,
     *        description="Invalid request"
     *    ),
     *    @OA\Response(
     *        response=403,
     *        description="Unauthorized Access"
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="not found"
     *    ),
     *   security={{ "apiAuth": {} }}
    *)
    */
    public function activate(Request $request,$id)
    {
        $checkProduct = Product::where('id', $id)->first();
        if(!$checkProduct){
            return response()->json(['Error' => 'Product not Found...']);
        } else {

            $attributes = array();
            $attributes['is_active'] = 1;
            $products = Product::where('id', $id)->update($attributes);
            return response()->json(['Success' => 'Product Activate Successfully', 'view'=> []]);
        }
    }

    /**
    * @OA\Patch(
    *    path="/api/product/deactivate/{id}",
    *    tags={"Products"},
     *    summary="change Products status",
     *    operationId="deactivate_product",
     *    @OA\Parameter(
     *        name="id",
     *        in="path",
     *        required=true,
     *        @OA\Schema(
     *           type="integer",
     *           format="int64"
     *        )
     *    ),
     *    @OA\Response(
     *        response=200,
     *        description="Success",
     *        @OA\MediaType(
     *            mediaType="application/json",
     *        )
     *    ),
     *    @OA\Response(
     *        response=401,
     *        description="Unauthorized"
     *    ),
     *    @OA\Response(
     *        response=400,
     *        description="Invalid request"
     *    ),
     *    @OA\Response(
     *        response=403,
     *        description="Unauthorized Access"
     *    ),
     *    @OA\Response(
     *        response=404,
     *        description="not found"
     *    ),
     *   security={{ "apiAuth": {} }}
    *)
    */
    public function deactivate(Request $request,$id)
    {
        $checkProduct = Product::where('id', $id)->first();
        if(!$checkProduct){
            return response()->json(['Error' => 'Product not Found...']);
        } else {

            $attributes = array();
            $attributes['is_active'] = 0;
            $products = Product::where('id', $id)->update($attributes);
            return response()->json(['Success' => 'Product De-activate Successfully', 'view'=> []]);
        }
    }

    /**
	* @OA\Get(
	*    path="/api/product/details/{id}",
	 *    tags={"Products"},
	 *    summary="Product detail",
	 *    operationId="detail",
	 *    @OA\Parameter(
	 *        name="id",
	 *        in="path",
	 *        required=true,
	 *        @OA\Schema(
	 *           type="integer",
	 *           format="int64"
	 *        )
	 *    ),
	 *    @OA\Response(
	 *        response=200,
	 *        description="Success",
	 *        @OA\MediaType(
	 *            mediaType="application/json",
	 *        )
	 *    ),
	 *    @OA\Response(
	 *        response=401,
	 *        description="Unauthorized"
	 *    ),
	 *    @OA\Response(
	 *        response=400,
	 *        description="Invalid request"
	 *    ),
	 *    @OA\Response(
	 *        response=403,
	 *        description="Unauthorized Access"
	 *    ),
	 *    @OA\Response(
	 *        response=404,
	 *        description="not found"
	 *    ),
	 *   security={{ "apiAuth": {} }}
	 *)
	*/
	public function view(Request $request, $id)
	{        
        $checkProduct = Product::where('id', $id)->first();
        if(!$checkProduct){
            return response()->json(['Error' => 'Product not Found...']);
        } else {
            return response()->json(['Success' => 'Product', 'view' => $checkProduct]);
        }
	}
}
