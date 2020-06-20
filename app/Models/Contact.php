<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model 
{

    protected $table = 'contacts';
    public $timestamps = true;
    protected $fillable = array('fullname', 'email', 'phone', 'message', 'type', 'contactable_id', 'contactable_type');

    public function contactable()
    {
        return $this->morphTo();
    }

}