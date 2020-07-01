<?php

namespace App\Http\Controllers\Api\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OfferController extends Controller
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
        $records = $ownerRestaurant->offers()->paginate(20);
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

        // dd(NOW());
        //date_format: Y-m-d      H:i:s     example
        //             2020-06-30 23:57:04

        $rules = [
            'name'          => 'required|min:3',
            'description'   => 'required|min:10', 
            'image'         => 'required',
            'from'          => 'required|date_format:Y-m-d H:i:s|after_or_equal:NOW|before:to', 
            'to'            => 'required|date_format:Y-m-d H:i:s|after:from', 
            'price'         => 'required|numeric',
        ];

        $validator = validator()->make($request->all(), $rules);

        if($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }    

        $ownerRestaurant = $request->user();

        $record = $ownerRestaurant->offers()->create($request->all());

        if(!$record) {
            return responseJson(0, 'creation failed');
        }

        return responseJson(1, 'Offer created successfully', $record);
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

        $record = $ownerRestaurant->offers()->find($id);

        if(!$record) {
            return responseJson(0, "failed to find an offer with this id in this restaurant");
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
        $rules = [
            'name'          => 'min:3',
            'description'   => 'min:10', 
            'image'         => '',
            'from'          => 'date_format:Y-m-d H:i:s|after_or_equal:NOW|before:to', 
            'to'            => 'date_format:Y-m-d H:i:s|after:from', 
            'price'         => 'numeric',
        ];

        $validator = validator()->make($request->all(), $rules);

        if($validator->fails()) {
            return responseJson(0, $validator->errors()->first(), $validator->errors());
        }    

        $ownerRestaurant = $request->user();

        $record = $ownerRestaurant->offers()->find($id);

        if(!$record) {
            return responseJson(0, 'can not find an offer with this id which belongs to this restaurant');
        }

        $update = $record->update($request->all());

        if(!$update) {
            return responseJson(0, 'update failed');
        }

        return responseJson(1, 'Offer updated successfully', $record);
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

        $record = $ownerRestaurant->offers()->find($id);

        if(!$record) {
            return responseJson(0, 'failed to find an offer with this id in this restaurant');
        }

        $delete = $record->delete();

        if(!$delete) {
            return responseJson(0, 'delete failed');
        }
        
        return responseJson(1, 'Category deleted successfully');
    }
}
