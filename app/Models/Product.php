<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model 
{

    protected $table = 'products';
    public $timestamps = true;
    protected $fillable = array('name', 'description', 'price', 'offer_price', 'image', 'category_id');
    protected $appends = array('has_offer', 'current_price');


    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function orders()
    {
        return $this->belongsToMany('App\Models\Order');
    }

    public function getHasOfferAttribute() {
        return $this->offer_price ? ($this->offer_price < $this->price) : false;
    }

    public function getCurrentPriceAttribute() {
        return $this->has_offer ? $this->offer_price : $this->price;
    }

}