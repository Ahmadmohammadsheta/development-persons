<?php
namespace App\Repository;

interface StudentRepositoryInterface {


    /**
    * Method for data conditions where boolean name
    */
    public function whereRelationColumnName(array $attributes);

    /**
     * My Final Method for all data conditions functional
     */
    public function shetaForAllConditionsWithRelations(array $attributes, $relations = [], $builder = 'get');
}
