<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model 
{

    protected $table = 'app_settings';
    public $timestamps = true;
    protected $fillable = array('about_us_text', 'commissions_page_text', 'commissions_rate', 'payment_account_details');

}