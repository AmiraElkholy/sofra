<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Carbon\Carbon;


class Offer extends Model 
{

    protected $table = 'offers';
    public $timestamps = true;
    protected $fillable = array('name', 'description', 'image', 'from', 'to', 'restaurant_id', 'price');

    public function restaurant()
    {
        return $this->belongsTo('App\Models\Restaurant');
    }

    public function setFromAttribute($value) {
    	$this->attributes['from'] = Carbon::parse($value)->format('Y-m-d H:i:s');
    }

    public function setToAttribute($value) {
    	$this->attributes['to'] = Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}