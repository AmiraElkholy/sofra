<?php

namespace App\Http\Controllers\Api\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;


class ProductController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api-restaurant');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $category_id)
    {
        $category = Category::find($category_id);
        
        if(!$category) {
            return responseJson(0, 'failed to find category with this id');
        }

        $owenrRestaurant = $request->user();

        if(!$owenrRestaurant->categories->contains($category)) {
            return responseJson(0, 'no category with this id belongs to this restaurant');
        }

        $records = $category->products()->paginate(20);
        
        return responseJson(1, 'success', $records);
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
    public function store(Request $request, $category_id)
    {
        $rules = [
            'name'          => 'required|min:3', 
            'description'   => 'required|min:10',
            'price'         => 'required|numeric',
            'offer_price'   => 'numeric|nullable|less_than_or_equal_field:price',
            'image'         => 'required'
        ];

        $msg = [
            'less_than_or_equal_field' => 'offer price must be less than or equal to actual price'
        ];

        $validator = validator()->make($request->all(), $rules, $msg);

        if($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }    

        $category = Category::find($category_id);
        
        if(!$category) {
            return responseJson(0, 'failed to find category with this id');
        }

        $owenrRestaurant = $request->user();

        if(!$owenrRestaurant->categories->contains($category)) {
            return responseJson(0, 'no category with this id belongs to this restaurant');
        }

        $record = $category->products()->create($request->all());

        if(!$record) {
            return responseJson(0, 'creation failed');
        }

        return responseJson(1, 'Product created successfully', $record);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $category_id, $id)
    {
        $category = Category::find($category_id);
        
        if(!$category) {
            return responseJson(0, 'failed to find category with this id');
        }

        $owenrRestaurant = $request->user();

        if(!$owenrRestaurant->categories->contains($category)) {
            return responseJson(0, 'no category with this id belongs to this restaurant');
        }

        $record = $category->products()->find($id);

        if(!$record) {
            return responseJson(0, "can't find product with this id in this category");
        }        

        return responseJson(1, 'success', $record);
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
    public function update(Request $request, $category_id, $id)
    {
        $category = Category::find($category_id);
        
        if(!$category) {
            return responseJson(0, 'failed to find category with this id');
        }

        $owenrRestaurant = $request->user();

        if(!$owenrRestaurant->categories->contains($category)) {
            return responseJson(0, 'no category with this id belongs to this restaurant');
        }

        $record = $category->products()->find($id);

        if(!$record) {
            return responseJson(0, 'no product with this id in this category');
        }        

        $rules = [
            'name'          => 'min:3', 
            'description'   => 'min:10',
            'price'         => 'numeric',
            'offer_price'   => 'numeric|nullable|less_than_or_equal_field:price',
            'image'         => ''
        ];

        $msg = [
            'less_than_or_equal_field' => 'offer price must be less than or equal to actual price'
        ];

        $validator = validator()->make($request->all(), $rules, $msg);

        if($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }    

        $update = $record->update($request->all());

        if(!$update) {
            return responseJson(0, 'update failed');
        }

        return responseJson(1, 'Product updated successfully', $record);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $category_id, $id)
    {
        $category = Category::find($category_id);
        
        if(!$category) {
            return responseJson(0, 'failed to find category with this id');
        }

        $owenrRestaurant = $request->user();

        if(!$owenrRestaurant->categories->contains($category)) {
            return responseJson(0, 'no category with this id belongs to this restaurant');
        }

        $record = $category->products()->find($id);

        if(!$record) {
            return responseJson(0, 'no product with this id in this category');
        }

        $delete = $record->delete();

        if(!$delete) {
            return responseJson(0, 'delete failed');
        }

        return responseJson(1, 'success');
    }
}
