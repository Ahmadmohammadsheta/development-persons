<?php

namespace App\Repository\Eloquent;

// use App\Models\Item;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use App\Repository\EloquentRepositoryInterface;
use App\Http\Traits\ResponseTrait as TraitResponseTrait;
use App\Http\Traits\AuthGuardTrait as TraitsAuthGuardTrait;

class BaseRepository implements EloquentRepositoryInterface
{
    use TraitResponseTrait;
    use TraitsAuthGuardTrait;

    /**
     * @var Model
     */
    protected $model;

    /**
     * Resource Class
     */
    protected $resourceCollection;



    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * @param array $attributes
     *
     * @return Model
     */
    public function create(array $attributes): Model
    {
        // $attributes['created_by'] = $this->getTokenId('user');
        return $this->model->create($attributes);
    }

    /**
     * @param $id
     * @return Model
     */
    public function find($id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * @param id $attributes
     * @return Model
     */
    public function edit($id, array $attributes)
    {
        $data = $this->model->findOrFail($id);
        $attributes['updated_by'] = $this->getTokenId('user');
        $data->update($attributes);
        return $data;
    }


    public function delete($id): ?Model
    {
        $data = $this->model->findOrFail($id);

        $data->delete();
        return $data;
    }

    public function forceDelete($id): ?Model
    {
        return $this->model->onlyTrashed()->findOrFail($id)->forceDelete();
    }

    /**
     * Write code on Method
     *
     * @return response
     */
    public function forceDeleteAll()
    {
        return $this->model->onlyTrashed()->forceDelete();
    }

    /**
     * Write code on Method
     * @param $id
     * @return Model
     */
    public function restore($id)
    {
        return $this->model->withTrashed()->findOrFail($id)->restore();
    }

    /**
     * Write code on Method
     *
     * @return Model
     */
    // public function getDelete()
    // {
    //     return $this->model->withTrashed()->get();

    // }

    public function restoreAll()
    {
        return $this->model->onlyTrashed()->restore();
    }

    /**
     * Method for data conditions where column name
     */
    public function whereColumnName(array $attributes)
    {
        return function ($q) use ($attributes) {
            !array_key_exists('columnName', $attributes) || $attributes['columnValue'] == 0  ?: $q
                ->where([$attributes['columnName'] => $attributes['columnValue']]);
        };
    }

    public function forApprovedItems(array $attributes)
    {
        return function ($q) use ($attributes) {
            !array_key_exists('approved', $attributes) ?: $q
                ->where(['approved' => $attributes['approved']]);
        };
    }


    /**
     * Method for data conditions where boolean name
     */
    public function whereBooleanName(array $attributes)
    {
        return function ($q) use ($attributes) {
            !array_key_exists('booleanName', $attributes)   ?: $q
                ->where([$attributes['booleanName'] => $attributes['booleanValue']]);
        };
    }

    /**
     * Method for data conditions where parent category id
     */
    public function whereCategoryParentId(array $attributes)
    {
        return function ($q) use ($attributes) {
            !array_key_exists('parent_id', $attributes) || $attributes['parent_id'] == 0   ?: $q
                ->whereHas('category', function ($q) use ($attributes) {
                    $q->where(['parent_id' => $attributes['parent_id']]);
                });
        };
    }


    public function whereProjectId(array $attributes)
    {
        return function ($q) use ($attributes) {
            !array_key_exists('project_id', $attributes) || $attributes['project_id'] == 0   ?: $q
                ->whereHas('project', function ($q) use ($attributes) {
                    $q->where(['project_id' => $attributes['project_id']]);
                });
        };
    }

    /**
     * Method for data conditions where category id
     */
    public function whereCategoryId(array $attributes)
    {
        return function ($q) use ($attributes) {
            !array_key_exists('category_id', $attributes) || $attributes['category_id'] == 0   ?: $q
                ->where(['category_id' => $attributes['category_id']]);
        };
    }



    /**
     * Method for data searching where key words
     */
    public function searchingWherekeyWords(array $attributes)
    {
        return function ($q) use ($attributes) {
            !array_key_exists('key_words', $attributes) ?: (!is_numeric($attributes['key_words']) ? $q
                ->where('key_words', 'LIKE', "%{$attributes['key_words']}%") : $q
                ->whereBetween('sale_price', [$attributes['key_words'] - 25, $attributes['key_words'] + 25]));
        };
    }

    public function searchingWhereprice(array $attributes)
    {

        return function ($q) use ($attributes) {

            !array_key_exists('price', $attributes) || (!is_numeric($attributes['price']) ? $q
                ->where('key_words', 'LIKE', "%{$attributes['price']}%") : $q
                ->where('sale_price', '<=', $attributes['price'])
                ->where('sale_price', '!=', 0)
                );

    };

}


    /**
     * Method for data where unit boolean column
     */
    public function whereUnitBooleanColumn(array $attributes)
    {
        return function ($q) use ($attributes) {
            !array_key_exists('unitBooleanColumn', $attributes) ?: $q
                ->whereHas('unit', function ($q) use ($attributes) {
                    $q->where([$attributes['unitBooleanColumn'] => $attributes['booleanValue']]);
                });
        };
    }

    /**
     * Method for data where relation boolean column
     */

    public function whereBelongsToRelationHasBooleanColumn(array $attributes)
    {
        return function ($q) use ($attributes) {
            !array_key_exists('relationBooleanColumn', $attributes) ?: $q
                ->whereHas($attributes['relation'], function ($q) use ($attributes) {
                    $q->where([$attributes['relationBooleanColumn'] => $attributes['boolean']]);
                });
        };
    }

    /**
     * Method for data where discount
     */
    public function whereDiscount(array $attributes)
    {
        return function ($q) use ($attributes) {
            !array_key_exists('discount', $attributes) || !array_key_exists('sale_price_two', $attributes) ?: $q
                ->where('discount', '>', 0)->orwhere('sale_price_two', '>', 0);

        };
    }

    public function whereNoParent(array $attributes)
    {
        return function ($q) use ($attributes) {
            !array_key_exists('parent', $attributes)?:
            $q->where($attributes['parent'], '<>',0 );

        };
    }


    /**
     * Method for data where discount
     */
    public function theLatest(array $attributes)
    {
        return function ($q) use ($attributes) {
            !array_key_exists('latest', $attributes) ?: $q
                ->latest();
        };
    }


    /**
     * Method for all data conditions functional
     */
    public function forAllConditions(array $attributes)
    {
        return $this->model
            ->where($this->whereColumnName($attributes))
            ->where($this->whereBooleanName($attributes))
            ->where($this->whereCategoryParentId($attributes))
            ->where($this->whereCategoryId($attributes))
            ->where($this->searchingWherekeyWords($attributes))
            ->where($this->whereNoParent($attributes))
            ->where($this->whereDiscount($attributes))
            ->where($this->theLatest($attributes))
            ->where($this->searchingWhereprice($attributes))
            ->where($this->whereBelongsToRelationHasBooleanColumn($attributes))
            ->where($this->whereUnitBooleanColumn($attributes))
            ->where($this->whereBelongsToRelationHasBooleanColumn($attributes))
            ->where($this->whereDiscount($attributes))
            ->where($this->theLatest($attributes))
            ->where($this->whereProjectId($attributes))
            ->where($this->searchingWhereprice($attributes))
            ->where($this->forApprovedItems($attributes));

    }

    // public function forConditionsItemsPrice(array $attributes)
    // {
    //     return $this->model

    //         ->where($this->searchingWherekeyWords($attributes))
    //         ->where($this->whereDiscount($attributes))
    //         ->where($this->searchingWhereprice($attributes));

    // }


    /**
     * Method for all data conditions to latest
     */
    public function forAllConditionsLatest(array $attributes, $resourceCollection)
    {
        $this->resourceCollection = $resourceCollection;

        return
            $this->paginateResponse(
                $this->resourceCollection::collection($this->forAllConditions($attributes)
                    ->latest()->limit(array_key_exists('limit', $attributes) ? $attributes['limit'] : "")
                    ->paginate(array_key_exists('count', $attributes) ? $attributes['count'] : "")),
                $this->forAllConditions($attributes)->latest()->limit(array_key_exists('limit', $attributes) ? $attributes['limit'] : "")
                    ->paginate(array_key_exists('count', $attributes) ? $attributes['count'] : ""),
                "Latest data; Youssof",
                200
            );
    }


    public function forAllConditionsitems(array $attributes, $resourceCollection)
    {
        $this->resourceCollection = $resourceCollection;

        return

            $this->paginateResponse(
                $this->resourceCollection::collection(
                    $this->forAllConditions($attributes)
                        ->orderBy('sale_price', 'DESC')
                        ->paginate(array_key_exists('count', $attributes) ? $attributes['count'] : "")
                ),
                $this->forAllConditions($attributes)->paginate(array_key_exists('paginate', $attributes) ? $attributes['paginate'] : ""),
                "paginate data; Youssof",
                200
            );
    }



    /**
     * Method for all data conditions to random
     */
    public function forAllConditionsRandom(array $attributes, $resourceCollection)
    {
        $this->resourceCollection = $resourceCollection;

        return
            $this->sendResponse(
                $this->resourceCollection::collection($this->forAllConditions($attributes)->inRandomOrder()->limit(array_key_exists('count', $attributes) ? $attributes['count'] : "")->get()),
                "Random data; Youssof",
                200
            );
    }

    /**
     * Method for all data conditions to paginate
     */
    public function forAllConditionsPaginate(array $attributes, $resourceCollection)
    {
        $this->resourceCollection = $resourceCollection;

        return
            $this->paginateResponse(
                $this->resourceCollection::collection($this->forAllConditions($attributes)
                    ->paginate(array_key_exists('count', $attributes) ? $attributes['count'] : "")),
                $this->forAllConditions($attributes)->paginate(array_key_exists('count', $attributes) ? $attributes['count'] : ""),
                "paginate data; Youssof",
                200
            );
    }
    public function ItemsforAllConditionsPaginate(array $attributes, $resourceCollection)
    {
        $this->resourceCollection = $resourceCollection;

        return
            $this->paginateResponse(
                $this->resourceCollection::collection($this->forAllConditions($attributes)
                    ->orderBy('sale_price', 'DESC')
                    ->paginate(array_key_exists('count', $attributes) ? $attributes['count'] : "")
                    ),
                $this->forAllConditions($attributes)->paginate(array_key_exists('count', $attributes) ? $attributes['count'] : ""),
                "paginate data; Youssof",
                200
            );
    }




    /**
     * Method for all data conditions to paginate
     */
    public function allModelsArchived(array $attributes, $resourceCollection)
    {
        $this->resourceCollection = $resourceCollection;

        return
            $this->paginateResponse(
                $this->resourceCollection::collection($this->model->onlyTrashed()->paginate(array_key_exists('count', $attributes) ? $attributes['count'] : "")),
                $this->model->onlyTrashed()->paginate(array_key_exists('count', $attributes) ? $attributes['count'] : ""),
                "archived data; Youssof",
                200
            );
    }

    /**
     * Method for all data conditions to return a wich method filtered by attributes
     */
    public function forAllConditionsReturn(array $attributes, $resourceCollection)
    {
        $this->resourceCollection = $resourceCollection;

        return array_key_exists('paginate', $attributes) ? $this->forAllConditionsPaginate($attributes, $resourceCollection)
            : (array_key_exists('latest', $attributes) ? $this->forAllConditionsLatest($attributes, $resourceCollection)
            : (array_key_exists('archived', $attributes) ? $this->allModelsArchived($attributes, $resourceCollection)
            //: (array_key_exists('paginate', $attributes) ? $this->forAllConditions($attributes, $resourceCollection)
            : $this->forAllConditionsRandom($attributes, $resourceCollection)
        ));
    }

    //items

    public function forAllConditionsReturnItems(array $attributes, $resourceCollection)
    {
        $this->resourceCollection = $resourceCollection;

        return array_key_exists('paginate', $attributes) ? $this->ItemsforAllConditionsPaginate($attributes, $resourceCollection)
            : (array_key_exists('latest', $attributes) ? $this->forAllConditionsLatest($attributes, $resourceCollection)
            : (array_key_exists('archived', $attributes) ? $this->allModelsArchived($attributes, $resourceCollection)
            : (array_key_exists('price', $attributes) ? $this->forAllConditionsitems($attributes, $resourceCollection)

                        : $this->forAllConditionsRandom($attributes, $resourceCollection))));
    }

    /**
     * @param id $attributes
     * @return Model
     */
    public function toggleUpdate($id, $booleanName)
    {
        $data = $this->model->find($id);
        $data->update([$booleanName => !$data[$booleanName]]);
        return $data;
    }
}
