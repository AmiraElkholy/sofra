<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * Class District
 * @package App\Models
 * @version July 12, 2020, 12:06 am UTC
 *
 * @property \App\Models\City $city
 * @property \Illuminate\Database\Eloquent\Collection $clients
 * @property \Illuminate\Database\Eloquent\Collection $restaurants
 * @property string $name
 * @property integer $city_id
 */
class District extends Model
{

    public $table = 'districts';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'name',
        'city_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'city_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|min:2',
        'city_id' => 'required|integer|exists:cities,id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function city()
    {
        return $this->belongsTo(\App\Models\City::class, 'city_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function clients()
    {
        return $this->hasMany(\App\Models\Client::class, 'district_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function restaurants()
    {
        return $this->hasMany(\App\Models\Restaurant::class, 'district_id');
    }
}
