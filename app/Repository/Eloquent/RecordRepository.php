<?php

namespace App\Repository\Eloquent;

use App\Models\Record;
use App\Repository\RecordRepositoryInterface;
use Carbon\Carbon;

class RecordRepository extends BaseRepository implements RecordRepositoryInterface
{
   /**
    * RecordRepository constructor.
    *
    * @param Record $model
    */
   public function __construct(Record $model)
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
               ->where('created_at', Carbon::today())
               ->$builder(array_key_exists('limit', $attributes) ? $attributes['limit'] : "")
           // else
           : ($this->forAllConditions($attributes)->limit(array_key_exists('limit', $attributes) ? $attributes['limit'] : "")
            ->whereHas('student', function ($q) {
                $q->orderBy('birthdate', 'ASC');
            })
            ->whereDate('created_at', Carbon::today())
            ->$builder()
       )));
   }
}
