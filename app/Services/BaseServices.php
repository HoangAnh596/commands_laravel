<?php

namespace App\Services;

interface BaseServices
{
    /**
     * Create model record
     *
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes);

    /**
     * Find model record for given id
     *
     * @param int $id
     * @param array $columns
     * @return Model
     */
    public function find($id, $columns = ['*']);

    /**
     * findOrFail model
     *
     * @param $id
     * @param array $column
     * @return mixed
     */
    public function findOrFail($id, $column = ['*']);

    /**
     * Update model record for given id
     *
     * @param int $id
     * @param array $attributes
     * @return Model
     */
    public function update($id, array $attributes);

    /**
     * Delete model record for given id
     *
     * @param int $id
     * @return bool
     */
    public function delete($id);

    /**
     * List all model records
     *
     * @param array $columns
     * @return Collection
     */
    public function all($columns = ['*']);

    /**
     * Paginate records for scaffold.
     *
     * @param int $perPage
     * @param array $columns
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage, $search = [], $columns = ['*']);

    /**
     * Build a query for retrieving all records.
     *
     * @param array $search
     * @param int|null $skip
     * @param int|null $limit
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function allQuery($search = [], $skip = null, $limit = null);

    /**
     * Retrieve first record by given value column
     *
     * @param string $column
     * @param $value
     * @return Model
     */
    public function findOneByColumn($column, $value);

    /**
     * Get first or create new record
     *
     * @param array $conditions
     * @param array $attributes
     * @return Model
     */
    public function firstOrCreate(array $conditions, array $attributes = []);
}
