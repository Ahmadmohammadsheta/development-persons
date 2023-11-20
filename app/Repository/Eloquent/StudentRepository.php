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
}
