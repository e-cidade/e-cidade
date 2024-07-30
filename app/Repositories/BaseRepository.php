<?php

namespace App\Repositories;

use App\Repositories\Interfaces\EloquentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;

abstract class BaseRepository implements EloquentRepositoryInterface
{

    protected $limit = 10;
    protected $skip = 0;
    protected $model = null;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Get a list of results
     *
     * @param array $columns
     * @param string $orderColumn
     * @return Collection
     */
    public function all($columns = array('*'), $orderColumn = 'id'): Collection
    {
        return $this->model->orderByDesc($orderColumn)->get($columns);
    }

    /**
     * Get a list of results with limit
     *
     * @param array $columns
     * @return Collection
     */
    public function allLimited($columns = array('*')): Collection
    {
        return $this->model->skip($this->skip)->take($this->limit)->get($columns);
    }

    /**
     * Get a single result
     *
     * @param int|string $id
     *
     * @return Model
     */
    public function find($id, $columns = array('*')): ?Model
    {
        return $this->model->find($id, $columns);
    }

    /**
     * Load information of another relationship
     *
     * @param array $relations Array with the relationship name
     *
     * @return Collection
     */
    public function load(array $relations): Collection
    {
        return $this->model->load($relations)->get();
    }

    /**
     * Get results with paginate
     *
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate($perPage = 15, $columns = array('*'))
    {
        return $this->model->paginate($perPage, $columns);
    }

    /**
     * Create a new record
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data): ?Model
    {
        return $this->model->create($data);
    }

    /**
     * Create new records
     *
     * @param array $data
     * @return bool
     */
    public function createMany(array $data): ?bool
    {
        return $this->model->insert($data);
    }

    /**
     * Update a record
     *
     * @param array $data
     * @param int|string $id
     * @param string $field
     * @return bool
     */
    public function update(array $data, $id, $field = "id"): bool
    {
        return $this->model->where($field, '=', $id)->update($data);
    }

    /**
     * Delete a record
     *
     * @param $id
     * @return bool
     */
    public function delete($id): bool
    {
        return $this->model->destroy($id);
    }

    /**
     * Delete records with condition
     *
     * @param string $field
     * @param int|string $value
     * @return bool
     */
    public function deleteBy($field, $value): bool
    {
        return $this->model->where($field, $value)->delete();
    }

    /**
     * Get a single result by condition
     *
     * @param string $field
     * @param int|string $value
     * @param array $columns
     * @return Model
     */
    public function findBy($field, $value, $columns = array('*')): ?Model
    {
        return $this->model->where($field, '=', $value)->first($columns);
    }

    /**
     * Get results with multiple conditions
     * need to be tested
     * @param $match
     * @param array $columns
     * @return Collection
     */
    public function findMultipleWhere($match, $columns = array('*')): Collection
    {
        return $this->model->where($match)->get($columns);
    }

    /**
     * start DB transaction
     *
     * @return void
     */
    public function beginTransaction()
    {
        DB::beginTransaction();
    }

    /**
     * finish DB transaction
     *
     * @return void
     */
    public function commit()
    {
        DB::commit();
    }
}

