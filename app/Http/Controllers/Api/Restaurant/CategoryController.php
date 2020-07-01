<?php

namespace App\Http\Controllers\Api\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use App\Models\Category;


class CategoryController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api-restaurant');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $ownerRestaurant = $request->user(); 
        $records = $ownerRestaurant->categories()->paginate(20);
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
    public function store(Request $request)
    {
        $rules = [
            'name'  => 'required|min:3', 
            'image' => 'required'
        ];

        $validator = validator()->make($request->all(), $rules);

        if($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }    

        $ownerRestaurant = $request->user();

        $record = $ownerRestaurant->categories()->create($request->all());

        if(!$record) {
            return responseJson(0, 'creation failed');
        }

        return responseJson(1, 'Category created successfully', $record);    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $ownerRestaurant = $request->user();

        $record = $ownerRestaurant->categories()->find($id);

        if(!$record) {
            return responseJson(0, "failed to find a category with this id in this restaurant");
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
    public function update(Request $request, $id)
    {

        $ownerRestaurant = $request->user();

        $record = $ownerRestaurant->categories()->find($id);

        if(!$record) {
            return responseJson(0, "failed to find category with this id in this restaurant");
        }

        $rules = [
            'name'  => 'required|min:3',
            'image' => 'required'
        ];

        $validator = validator()->make($request->all(), $rules);

        if($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }    

        $update = $record->update($request->all());

        if(!$update) {
            return responseJson(0, 'update failed');
        }

        return responseJson(1, 'Category updated successfully', $record);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $ownerRestaurant = $request->user();

        $record = $ownerRestaurant->categories()->find($id);

        if(!$record) {
            return responseJson(0, "failed to find category with this id in this restaurant");
        }
        
        if($record->products()->count()) {
            return responseJson(0, "Category can't be deleted. There are related products.");
        }

        $delete = $record->delete();

        if(!$delete) {
            return responseJson(0, 'delete failed.');
        }
        
        return responseJson(1, 'Category deleted successfully');
    }
}
