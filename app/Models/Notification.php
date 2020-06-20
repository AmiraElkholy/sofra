<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model 
{

    protected $table = 'notifications';
    public $timestamps = true;
    protected $fillable = array('order_id', 'title', 'content', 'notifiable_id', 'notifiable_type', 'is_seen');

    public function user()
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function notifiable()
    {
        return $this->morphTo();
    }

    public function order()
    {
        return $this->belongsTo('App\Models\Order');
    }

}