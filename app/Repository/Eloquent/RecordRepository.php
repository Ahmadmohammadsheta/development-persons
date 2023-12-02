<?php

namespace App\Repository\Eloquent;

use App\Models\Record;
use App\Repository\RecordRepositoryInterface;

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
}
