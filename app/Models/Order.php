<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model 
{

    protected $table = 'orders';
    public $timestamps = true;
    protected $fillable = array('notes', 'delivery_address', 'user_id', 'restaurant_id', 'sub_total', 'delivery_fees', 'total', 'payment_method_id', 'state', 'reason_for_rejection');

    public function paymentMethod()
    {
        return $this->belongsTo('App\Models\PaymentMethod');
    }

    public function restaurant()
    {
        return $this->belongsTo('App\Models\Restaurant');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function products()
    {
        return $this->belongsToMany('App\Models\Product');
    }

    public function notifications()
    {
        return $this->hasMany('App\Models\Notification');
    }

}