<?php

namespace App\Services\Impl;

use App\Services\BaseServices;

abstract class BaseServicesImpl implements BaseServices
{
    protected $repository;

    public function __construct()
    {
        $this->setRepository();
    }

    /**
     * Make repository to use
     *
     * @return void
     */
    private function setRepository()
    {
        $this->repository = app()->make($this->repository());
    }

    /**
     * Get repository
     *
     * @return class
     */
    abstract public function repository();

    /**
     * Get fillable fields of repository
     *
     * @return array
     */
    public function getFillableFields()
    {
        return $this->repository->getFillableFields();
    }

    /**
     * Create model record
     *
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes)
    {
        return $this->repository->create($attributes);
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
        return $this->repository->find($id, $columns);
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
        return $this->repository->update($id, $attributes);
    }

    /**
     * Delete model record for given id
     *
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        return $this->repository->delete($id);
    }

    /**
     * List all model records
     *
     * @param array $columns
     * @return Collection
     */
    public function all($columns = ['*'])
    {
        return $this->repository->all($columns);
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
        return $this->repository->allQuery($search, $skip, $limit);
    }

    public function findOrFail($id, $column = ['*'])
    {
        return $this->repository->findOrFail($id, $column);
    }

    public function findOneByColumn($column, $value)
    {
        return $this->repository->findOneByColumn($column, $value);
    }

    public function firstOrCreate(array $conditions, array $attributes = [])
    {
        return $this->repository->firstOrCreate($conditions, $attributes);
    }
}
