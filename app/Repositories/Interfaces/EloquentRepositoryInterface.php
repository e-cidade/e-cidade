<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface EloquentRepositoryInterface
{

    /**
     * Get a list of results
     *
     * @param array $columns
     * @return Collection
     */
    public function all($columns = array('*')): Collection;

    /**
     * Get a list of results with limit
     *
     * @param array $columns
     * @return Collection
     */
    public function allLimited($columns = array('*')): Collection;

    /**
     * Get a single result
     *
     * @param int|string $id
     *
     * @return Model
     */
    public function find($id, $columns = array('*')): ?Model;

    /**
     * Load information of another relationship
     *
     * @param array $relations Array with the relationship name
     *
     * @return Collection
     */
    public function load(array $relations): Collection;

    /**
     * Get results with paginate
     *
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate($perPage = 15, $columns = array('*'));

    /**
     * Create a new record
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data): ?Model;

    /**
     * Update a record
     *
     * @param array $data
     * @param int|string $id
     * @param string $field
     * @return bool
     */
    public function update(array $data, $id, $field = "id"): bool;

    /**
     * Delete a record
     *
     * @param $id
     * @return bool
     */
    public function delete($id): bool;

    /**
     * Delete records with condition
     *
     * @param string $field
     * @param int|string $value
     * @return bool
     */
    public function deleteBy($field, $value): bool;

    /**
     * Get a single result by condition
     *
     * @param string $field
     * @param int|string $value
     * @param array $columns
     * @return Model
     */
    public function findBy($field, $value, $columns = array('*')): ?Model;

    /**
     * Get results with multiple conditions
     * need to be tested
     * @param $match
     * @param array $columns
     * @return Collection
     */
    public function findMultipleWhere($match, $columns = array('*')): Collection;
}

