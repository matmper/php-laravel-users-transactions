<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class BaseRepository
{
    /**
     * @var Model
     */
    protected $model;

    public function __construct()
    {
        $this->model = app($this->model);
    }

    /**
     * Eloquent model: find
     *
     * @param integer $id
     * @param array $columns
     * @param boolean $withTrashed
     * @return Model|null
     */
    public function find(int $id, array $columns = ['id'], bool $withTrashed = false): ?Model
    {
        $query = $this->getBaseQuery([], $columns, [], $withTrashed);
        
        return $query->find($id);
    }

    /**
     *  Eloquent model: findOrFail
     *
     * @param integer $id
     * @param array $columns
     * @param boolean $withTrashed
     * @return Model
     */
    public function findOrFail(int $id, array $columns = ['id'], bool $withTrashed = false): Model
    {
        $query = $this->getBaseQuery([], $columns, [], $withTrashed);
        
        return $query->findOrFail($id);
    }

    /**
     * Eloquent model: first
     *
     * @param array $where
     * @param array $columns
     * @param array $orderBy
     * @param boolean $withTrashed
     * @return Model|null
     */
    public function first(
        array $where,
        array $columns = ['id'],
        array $orderBy = [],
        bool $withTrashed = false
    ): ?Model {
        $query = $this->getBaseQuery($where, $columns, $orderBy, $withTrashed);
        
        return $query->first();
    }

    /**
     * Eloquent model: last
     *
     * @param array $where
     * @param array $columns
     * @param array $orderBy
     * @param boolean $withTrashed
     * @return Model|null
     */
    public function last(
        array $where,
        array $columns = ['id'],
        array $orderBy = [],
        bool $withTrashed = false
    ): ?Model {
        $query = $this->getBaseQuery($where, $columns, $orderBy, $withTrashed);
        
        return $query->latest()->first();
    }

    /**
     * Eloquent model: firstOrFail
     *
     * @param array $where
     * @param array $columns
     * @param array $orderBy
     * @param boolean $withTrashed
     * @return Model
     */
    public function firstOrFail(
        array $where,
        array $columns = ['id'],
        array $orderBy = [],
        bool $withTrashed = false
    ): Model {
        $query = $this->getBaseQuery($where, $columns, $orderBy, $withTrashed);
        
        return $query->firstOrFail();
    }

    /**
     * Eloquent model: get
     *
     * @param string|array $where
     * @param array $columns
     * @param array $orderBy
     * @param boolean $withTrashed
     * @return Collection
     */
    public function get(
        string|array $where,
        array $columns = ['id'],
        array $orderBy = [],
        bool $withTrashed = false,
        bool $baseQuery = false,
    ): Collection {
        $query = $this->getBaseQuery($where, $columns, $orderBy, $withTrashed);

        if ($baseQuery) {
            $query->toBase();
        }

        return $query->get();
    }

    /**
     * Eloquent model: count
     *
     * @param array $where
     * @param integer $limit (0 = No limit)
     * @param boolean $withTrashed
     * @return integer
     */
    public function count(array $where, int $limit = 0, bool $withTrashed = false): int
    {
        $query = $this->getBaseQuery($where, ['id'], [], $withTrashed);

        if (!empty($limit)) {
            $query->limit($limit);
        }
        
        return $query->count();
    }

    /**
     * Eloquent model: create (single row)
     *
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model
    {
        if (empty($data)) {
            return null;
        }

        return $this->model->create($data);
    }

    /**
     * Eloquent model: insert (multiple rows)
     *
     * @param array $data
     * @return boolean
     */
    public function insert(array $data): bool
    {
        if (empty($data)) {
            return null;
        }

        return $this->model->insert($data);
    }

    /**
     * Eloquent model: firstOrCreate
     *
     * @param array $where
     * @param array $fields
     * @return Model
     */
    public function firstOrCreate(array $where, array $fields = []): Model
    {
        if (empty($where)) {
            return null;
        }
        
        return $this->model->firstOrCreate($where, $fields);
    }

    /**
     * Eloquent model: updateOrCreate
     *
     * @param array $where
     * @param array $fields
     * @return Model
     */
    public function updateOrCreate(array $where, array $fields = []): Model
    {
        if (empty($where)) {
            return null;
        }
        
        return $this->model->updateOrCreate($where, $fields);
    }

    /**
     * Eloquent model: update (row by primary key)
     *
     * @param integer $itemPrimaryKey
     * @param array $data
     * @return Model|null
     */
    public function update(int $itemPrimaryKey, array $data): ?Model
    {
        if (empty($data)) {
            return null;
        }

        $collection = $this->model->find($itemPrimaryKey, ['*']);
        
        if (empty($collection)) {
            return null;
        }

        $collection->update($data);

        return $collection;
    }

    /**
     * Eloquent model: delete
     *
     * @param array $data
     * @return boolean
     */
    public function delete(int $itemPrimaryKey): bool
    {
        $collection = $this->model->find($itemPrimaryKey, ['id']);
        
        if (empty($collection)) {
            return false;
        }

        return $collection->delete();
    }

    /**
     * Create base query builder
     *
     * @param string|array $where
     * @param array $columns
     * @param array|null $orderBy
     * @param boolean $withTrashed
     * @return Builder
     */
    private function getBaseQuery(
        string|array $where,
        array $columns = ['id'],
        ?array $orderBy = [],
        bool $withTrashed = false
    ): Builder {
        $query = $this->model->query()->select($columns);

        $query = $this->scopeMakeWhere($query, $where);

        foreach ($orderBy as $key => $value) {
            $query->orderBy($key, $value);
        }
        
        if ($withTrashed) {
            $query->withTrashed();
        }

        return $query;
    }

    /**
     * Create where conditionals
     *
     * @param Builder $query
     * @param array $where
     * @return Builder
     */
    private function scopeMakeWhere(Builder $query, array $where): Builder
    {
        foreach ($where as $key => $value) {
            $keyExplode = explode(' ', $key);
            $condition = $keyExplode[1] ?? '=';

            // WHERE IN ['column' => [1,2]]
            if ($condition === '=' && is_array($value)) {
                $query->whereIn($keyExplode[0], $value);
            // WHERE NOT IN ['column !=' => [1,2]]
            } elseif ($condition === '!=' && is_array($value)) {
                $query->whereNotIn($keyExplode[0], $value);
            // WHERE IS NULL ['column' => null]
            } elseif ($condition === '=' && is_null($value)) {
                $query->whereNull($keyExplode[0]);
            // WHERE IS NOT NULL ['column !=' => null]
            } elseif ($condition === '!=' && is_null($value)) {
                $query->whereNotNull($keyExplode[0]);
            // WHERE DEFAULT
            } else {
                $query->where($keyExplode[0], $condition, $value);
            }
        }

        return $query;
    }
}
