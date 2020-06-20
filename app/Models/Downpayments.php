<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Downpayments extends Model 
{

    protected $table = 'downpayments';
    public $timestamps = true;
    protected $fillable = array('amount', 'restaurant_id', 'notes', 'date');

    public function restaurant()
    {
        return $this->belongsTo('App\Models\Restaurant');
    }

}