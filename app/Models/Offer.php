<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model 
{

    protected $table = 'offers';
    public $timestamps = true;
    protected $fillable = array('name', 'description', 'image', 'from', 'to', 'restaurant_id', 'price');

    public function restaurant()
    {
        return $this->belongsTo('App\Models\Restaurant');
    }

}