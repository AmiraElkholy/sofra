<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class Restaurant extends Authenticatable 
{

    use Notifiable;
      

    protected $table = 'restaurants';
    public $timestamps = true;
    protected $fillable = array('name', 'email', 'delivery_time', 'district_id', 'password', 'minimum_charge', 'delivery_fees', 'phone', 'whatsapp', 'image', 'is_open');
    protected $hidden = array('password', 'pin_code', 'api_token', 'rememberToken');
    protected $appends = array('availability');

    public function district()
    {
        return $this->belongsTo('App\Models\District');
    }

    public function categories()
    {
        return $this->hasMany('App\Models\Category');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    public function downpayments()
    {
        return $this->hasMany('App\Models\Downpayments');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\Review');
    }

    public function offers()
    {
        return $this->hasMany('App\Models\Offer');
    }

    public function notifications()
    {
        return $this->morphMany('App\Models\Notification', 'notifiable');
    }

    public function tokens()
    {
        return $this->morphMany('App\Models\Token', 'tokenable');
    }

    public function contacts()
    {
        return $this->morphMany('App\Models\Contact', 'contactable');
    }


    public function getAvailabilityAttribute() {
        // dd($this->is_open);
        return ($this->is_open) ? 'مفتوح' : 'مغلق';
    }

}