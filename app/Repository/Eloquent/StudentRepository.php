<?php

namespace App\Repository\Eloquent;

use App\Models\Student;
use App\Repository\StudentRepositoryInterface;

class StudentRepository extends BaseRepository implements StudentRepositoryInterface
{
   /**
    * StudentRepository constructor.
    *
    * @param Student $model
    */
   public function __construct(Student $model)
   {
       parent::__construct($model);
   }



    /**
     * My Final Method for all data conditions functional
     */
    public function shetaForAllConditions(array $attributes, $builder = 'get')
    {
        return
            // if the request has latest
            array_key_exists('latest', $attributes) ? $this->forAllConditions($attributes)->latest()
                ->take(array_key_exists('limit', $attributes) ? $attributes['limit'] : "")->$builder()
            // else if the request has random
            : (array_key_exists('random', $attributes) ? $this->forAllConditions($attributes)->inRandomOrder()
                ->limit(array_key_exists('limit', $attributes) ? $attributes['limit'] : "")->$builder()
            // else if the request has paginate
            : ($builder && $builder != 'get' ? $this->forAllConditions($attributes)
                ->orderBy('birthdate', 'ASC')
                ->$builder(array_key_exists('limit', $attributes) ? $attributes['limit'] : "")
            // else
            : ($this->forAllConditions($attributes)->limit(array_key_exists('limit', $attributes) ? $attributes['limit'] : "")
            ->orderBy('birthdate', 'ASC')
            ->$builder()
        )));
    }

    /**
     * Method for data conditions where boolean name
     */
    public function whereRelationColumnName(array $attributes, $relations = [])
    {
        return function ($q) use ($attributes, $relations) {
            !$relations ?: $q->whereHas($relations, function ($q) use ($attributes) {
                    $q->where([$attributes['relationColumnName'] => $attributes['relationColumnValue']]);
                });
            };
    }



    /**
     * My Final Method for all data conditions functional
     */
    public function shetaForAllConditionsWithRelations(array $attributes, $relations = [], $builder = 'get')
    {
        return
            // if the request has latest
            array_key_exists('latest', $attributes) ? $this->forAllConditions($attributes)->latest()
                ->take(array_key_exists('limit', $attributes) ? $attributes['limit'] : "")->$builder()
            // else if the request has random
            : (array_key_exists('random', $attributes) ? $this->forAllConditions($attributes)->inRandomOrder()
                ->limit(array_key_exists('limit', $attributes) ? $attributes['limit'] : "")->$builder()
                ->whereRelationColumnName($attributes, $relations)
            // else if the request has paginate
            : ($builder && $builder != 'get' ? $this->forAllConditions($attributes)
                ->orderBy('birthdate', 'ASC')
                ->$builder(array_key_exists('limit', $attributes) ? $attributes['limit'] : "")
            // else
            : ($this->forAllConditions($attributes)->limit(array_key_exists('limit', $attributes) ? $attributes['limit'] : "")
            ->orderBy('birthdate', 'ASC')
            ->$builder()
        )));
    }
}
