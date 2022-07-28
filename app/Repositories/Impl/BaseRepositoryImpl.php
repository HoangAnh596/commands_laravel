<?php

namespace App\Repositories\Impl;

use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepositoryImpl implements BaseRepository
{
    public $model;

    public function __construct()
    {
        $this->makeModel();
    }

    /**
     * Make model instance
     *
     * @return Model
     */
    protected function makeModel()
    {
        $model = app()->make($this->model());
        if (!$model instanceof Model) {
            throw new \Exception("Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model");
        }
        $this->model = $model;
    }

    /**
     * Configure the model
     *
     * @return class
     */
    abstract public function model();

    /**
     * Create model record
     *
     * @param array $attributes
     * @return Model
     */
    public function create($attributes)
    {
        $model = $this->model->newInstance($attributes);
        $model->save();
        return $model;
    }

    /**
     * Find model record for given id
     *
     * @param int $id
     * @param array $columns
     * @return Model
     */
    public function find($id, $columns = ['*'])
    {
        return $this->model->find($id, $columns);
    }

    /**
     * Update model record for given id
     *
     * @param int $id
     * @param array $attributes
     * @return Model
     */
    public function update($id, array $attributes)
    {
        $model = $this->find($id);
        $model->fill($attributes);
        $model->save();
        return $model;
    }

    /**
     * Delete model record for given id
     *
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $model = $this->model->findOrFail($id);
        return $model->delete();
    }

    /**
     * Get fillable fields of model
     *
     * @return array
     */
    public function getFillableFields()
    {
        $fillableFields = $this->model->getFillable();
        if (count($fillableFields) > 0) {
            return $fillableFields;
        }

        $guardedFields = $this->model->getGuarded();
        if (count($guardedFields) == 1 && $guardedFields[0] == '*') {
            return [];
        }

        $fields = Schema::getColumnListing($this->model->getTable());
        return array_diff($fields, $guardedFields);
    }

    /**
     * Get guarded fields of model
     *
     * @return array
     */
    public function getGuardedFields()
    {
        return $this->model->getGuarded();
    }

    /**
     * Get table name of model
     *
     * @return string
     */
    public function getTable()
    {
        return $this->model->getTable();
    }

    /**
     * Get all fields of model
     *
     * @return array
     */
    public function getAllFields()
    {
        return Schema::getColumnListing($this->getTable());
    }

    /**
     * List all model records
     *
     * @param array $columns
     * @return Collection
     */
    public function all($columns = ['*'])
    {
        return $this->model->all($columns);
    }

    /**
     * Paginate records for scaffold.
     *
     * @param int $perPage
     * @param array $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage, $search = [], $columns = ['*'])
    {
        return $this->allQuery($search)->paginate($perPage, $columns);
    }

    /**
     * Build a query for retrieving all records.
     *
     * @param array $search
     * @param int|null $skip
     * @param int|null $limit
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function allQuery($search = [], $skip = null, $limit = null)
    {
        $conditions = [];
        if (count($search)) {
            foreach ($search as $key => $value) {
                if (in_array($key, $this->getFillableFields())) {
                    $operator = $this->getOperator($value);
                    $value = trim(str_replace($operator, '', $value));
                    $conditions[] = [$key, $operator, $value];
                }
            }
        }

        $query = $this->model->where($conditions);

        if (!is_null($skip)) {
            $query = $query->skip($skip);
        }

        if (!is_null($limit)) {
            $query = $query->limit($limit);
        }

        return $query;
    }

    private function getOperator($inputString)
    {
        $inputString = trim($inputString);
        $firstElement = substr($inputString, 0, 1);
        $twoFirstElement = substr($inputString, 0, 2);
        $lastElement = substr($inputString, -1);

        if (in_array($twoFirstElement, ["<=", ">=", "<>"])) {
            return $twoFirstElement;
        }

        if ($firstElement == "%" || $lastElement == "%") {
            return "LIKE";
        }

        switch ($firstElement) {
            case ">":
                return ">";
                break;
            case "<":
                return "<";
                break;
            case "=":
            default:
                return "=";
                break;
        }

        return "=";
    }

    public function findOrFail($id, $column = ['*'])
    {
        return $this->model->findOrFail($id, $column);
    }

    public function findOneByColumn($column, $value)
    {
        return $this->model->firstWhere($column, $value);
    }

    public function firstOrCreate($conditions, $attributes = [])
    {
        return $this->model->firstOrCreate($conditions, $attributes);
    }

    public function whereIn($column, array $value)
    {
        return $this->model->whereIn($column, $value);
    }

    public function whereNotIn($column, array $value)
    {
        return $this->model->whereNotIn($column, $value);
    }
}
