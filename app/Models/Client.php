<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;



class Client extends Authenticatable 
{

    use Notifiable;

    protected $table = 'clients';
    public $timestamps = true;
    protected $fillable = array('name', 'email', 'phone', 'image', 'district_id', 'password');
    protected $hidden = array('password', 'pin_code', 'api_token', 'rememberToken');

    public function district()
    {
        return $this->belongsTo('App\Models\District');
    }

    public function orders()
    {
        return $this->hasMany('App\Models\Order');
    }

    public function reviews()
    {
        return $this->hasMany('App\Models\Review');
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

}