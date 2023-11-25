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
            ->where($this->searchingWherekeyWords($attributes))
            ->where($this->whereBelongsToRelationHasBooleanColumn($attributes))
            ->where($this->theLatest($attributes));
    }

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

    /**
     * Method for all data conditions to random
     */
    public function forAllConditionsIndex(array $attributes, $resourceCollection)
    {
        $this->resourceCollection = $resourceCollection;

        return
            $this->sendResponse(
                $this->resourceCollection::collection($this->forAllConditions($attributes)->limit(array_key_exists('count', $attributes) ? $attributes['count'] : "")->get()),
                "Index data; Youssof",
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
            : $this->forAllConditionsIndex($attributes, $resourceCollection)
        ));
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
