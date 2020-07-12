<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as Model;

/**
 * Class Category
 * @package App\Models
 * @version July 12, 2020, 12:41 am UTC
 *
 * @property \App\Models\Restaurant $restaurant
 * @property \Illuminate\Database\Eloquent\Collection $products
 * @property string $name
 * @property string $image
 * @property integer $restaurant_id
 */
class Category extends Model
{

    public $table = 'categories';
    
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';




    public $fillable = [
        'name',
        'image',
        'restaurant_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'name' => 'string',
        'image' => 'string',
        'restaurant_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'name' => 'required|min:2',
        'image' => 'required',
        'restaurant_id' => 'required|integer|exists:restaurants,id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function restaurant()
    {
        return $this->belongsTo(\App\Models\Restaurant::class, 'restaurant_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function products()
    {
        return $this->hasMany(\App\Models\Product::class, 'category_id');
    }
}
