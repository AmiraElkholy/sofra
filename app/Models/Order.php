<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model 
{

    protected $table = 'orders';
    public $timestamps = true;
    protected $fillable = array('notes', 'delivery_address', 'user_id', 'restaurant_id', 'sub_total', 'delivery_fees', 'total', 'commission', 'net', 'payment_method_id', 'state', 'reason_for_rejection');
    protected $appends = array('state_name');


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

    public function getStateNameAttribute() {
        $stateName = '';
        switch ($this->state) {
            case 'delivered':
                $stateName = 'الطلب مكتمل';
                break;
            
            case 'canceled':
                $stateName = 'الطلب ملغي';
                break;

            case 'rejected':
            case 'declined': 
                $stateName = 'الطلب مرفوض';
                break;
        }
        return $stateName;
    }


}