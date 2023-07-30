<?php

namespace App\Repositories;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class BaseRepository
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * Use Eloquent withTrashed method
     *
     * @var boolean
     */
    private $withTrashed;

    public function __construct()
    {
        $this->withTrashed = config('repository.default.with_trashed', false);
        $this->model = app($this->model);
    }

    /**
     * Set to use withTrashed() Eloquent method
     *
     * @return self
     */
    public function withTrashed(): self
    {
        $this->withTrashed = true;
        return $this;
    }

    /**
     * Init a new model query builder
     *
     * @return Builder
     */
    public function query(): Builder
    {
        return $this->model->query();
    }

    /**
     * Eloquent model: find
     *
     * @param integer $id
     * @param array $columns
     * @return Model|null
     */
    public function find(int $id, array $columns = ['id']): ?Model
    {
        $query = $this->getBaseQuery([], $columns, []);
        
        return $query->find($id);
    }

    /**
     *  Eloquent model: findOrFail
     *
     * @param integer $id
     * @param array $columns
     * @return Model
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException<\Illuminate\Database\Eloquent\Model>
     */
    public function findOrFail(int $id, array $columns = ['id']): Model
    {
        $query = $this->getBaseQuery([], $columns, []);
        
        return $query->findOrFail($id);
    }

    /**
     * Eloquent model: first
     *
     * @param array $where
     * @param array $columns
     * @param array $orderBy
     * @return Model|null
     */
    public function first(array $where, array $columns = ['id'], array $orderBy = []): ?Model
    {
        $query = $this->getBaseQuery($where, $columns, $orderBy);
        
        return $query->first();
    }

    /**
     * Eloquent model: firstOrFail
     *
     * @param array $where
     * @param array $columns
     * @param array $orderBy
     * @return Model
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException<\Illuminate\Database\Eloquent\Model>
     */
    public function firstOrFail(array $where, array $columns = ['id'], array $orderBy = []): Model
    {
        $query = $this->getBaseQuery($where, $columns, $orderBy);
        
        return $query->firstOrFail();
    }

    /**
     * Eloquent model: get
     *
     * @param array $where
     * @param array $columns
     * @param array $orderBy
     * @return Collection
     */
    public function get(
        array $where,
        array $columns = ['id'],
        array $orderBy = [],
        bool $baseQuery = false,
    ): Collection {
        $query = $this->getBaseQuery($where, $columns, $orderBy);

        if ($baseQuery) {
            $query->toBase();
        }

        return $query->get();
    }

    /**
     * Eloquent model: paginate
     *
     * @param array $where
     * @param array $columns
     * @param array $orderBy
     * @param int $limit
     * @return LengthAwarePaginator
     */
    public function paginate(
        array $where,
        array $columns = ['id'],
        array $orderBy = [],
        int $limit = 0,
    ): LengthAwarePaginator {
        $limit = $limit > 0 ? $limit : config('repository.default.paginate', 25);
        $query = $this->getBaseQuery($where, $columns, $orderBy);

        return $query->paginate($limit);
    }

    /**
     * Eloquent model: count
     *
     * @param array $where
     * @param integer $limit (0 = No limit)
     * @return integer
     */
    public function count(array $where): int
    {
        $query = $this->getBaseQuery($where, ['id'], []);
        
        return $query->count();
    }

    /**
     * Eloquent model: create (single row)
     *
     * @param array $data
     * @return Model
     * @throws Exception
     */
    public function create(array $data): Model
    {
        if (empty($data)) {
            throw new Exception('Base Repository: Array `$data` cannot be empty');
        }

        return $this->query()->create($data);
    }

    /**
     * Eloquent model: insert (multiple rows)
     *
     * @param array $data
     * @return boolean
     * @throws Exception
     */
    public function insert(array $data): bool
    {
        if (empty($data)) {
            throw new Exception('Base Repository: Array `$data` cannot be empty');
        }

        return $this->query()->insert($data);
    }

    /**
     * Eloquent model: firstOrCreate
     *
     * @param array $where
     * @param array $fields
     * @return Model
     * @throws Exception
     */
    public function firstOrCreate(array $where, array $fields = []): Model
    {
        if (empty($where)) {
            throw new Exception('Base Repository: Array `$where` cannot be empty');
        }
        
        return $this->query()->firstOrCreate($where, $fields);
    }

    /**
     * Eloquent model: updateOrCreate
     *
     * @param array $where
     * @param array $fields
     * @return Model
     * @throws Exception
     */
    public function updateOrCreate(array $where, array $fields = []): Model
    {
        if (empty($where)) {
            throw new Exception('Base Repository: Array `$where` cannot be empty');
        }
        
        return $this->query()->updateOrCreate($where, $fields);
    }

    /**
     * Eloquent model: update (row by primary key)
     *
     * @param integer $itemPrimaryKey
     * @param array $data
     * @return Model
     * @throws Exception
     */
    public function update(int $itemPrimaryKey, array $data): Model
    {
        if (empty($data)) {
            throw new Exception('Base Repository: Array `$data` cannot be empty');
        }

        $item = $this->findOrFail($itemPrimaryKey, ['*']);
        return $this->updateCollection($item, $data);
    }

    /**
     * Eloquent model: update save (collection)
     *
     * @param Model $item
     * @param array $data
     * @return Model
     * @throws Exception
     */
    public function updateCollection(Model $item, array $data): Model
    {
        if (empty($data)) {
            throw new Exception('Base Repository: Array `$data` cannot be empty');
        }

        foreach ($data as $key => $value) {
            $item->{$key} = $value;
        }

        $item->save();

        return $item;
    }

    /**
     * Eloquent model: delete
     *
     * @param array $data
     * @return boolean
     */
    public function delete(int $itemPrimaryKey): bool
    {
        return $this->query()->findOrFail($itemPrimaryKey, ['id'])->delete();
    }

    /**
     * Create base query builder
     *
     * @param array $where
     * @param array $columns
     * @param array|null $orderBy
     * @return Builder
     */
    private function getBaseQuery(array $where, array $columns = ['id'], ?array $orderBy = []): Builder
    {
        $query = $this->query()->select($columns);

        $query = $this->scopeMakeWhere($query, $where);

        foreach ($orderBy as $key => $value) {
            $query->orderBy($key, $value);
        }
        
        if ($this->withTrashed) {
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
            $keyExplode = explode(' ', trim($key));
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
