<?php

namespace App\Repositories;

use App\Models\City;
use App\Repositories\BaseRepository;

/**
 * Class CityRepository
 * @package App\Repositories
 * @version July 11, 2020, 7:09 pm UTC
*/

class CityRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return City::class;
    }
}
