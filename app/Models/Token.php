<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Token extends Model 
{

    protected $table = 'tokens';
    public $timestamps = true;
    protected $fillable = array('notification_token', 'tokenable_id', 'tokenable_type', 'platform');

    public function notifiable()
    {
        return $this->morphTo();
    }

}