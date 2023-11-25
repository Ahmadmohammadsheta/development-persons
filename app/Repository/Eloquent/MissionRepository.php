<?php

namespace App\Repository\Eloquent;

use App\Models\Mission;
use App\Repository\MissionRepositoryInterface;

class MissionRepository extends BaseRepository implements MissionRepositoryInterface
{
   /**
    * MissionRepository constructor.
    *
    * @param Mission $model
    */
   public function __construct(Mission $model)
   {
       parent::__construct($model);
   }
}
