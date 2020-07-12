<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * Class City
 * @package App\Models
 * @version July 11, 2020, 7:09 pm UTC
 *
 * @property \Illuminate\Database\Eloquent\Collection $districts
 * @property string $name
 */
class City extends Model
{

    public $table = 'cities';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'name'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        // 'created_at' => 'required',
        // 'updated_at' => 'required',
        'name' => 'required|min:2|unique:cities,name'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function districts()
    {
        return $this->hasMany(\App\Models\District::class, 'city_id');
    }
}
