<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
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
     * Retorna o Eloquent Find
     */
    public function find(int $id, string|array $colums = 'id', bool $withTrashed = false): ?object
    {
        $query = $this->model
            ->select($colums);

        if ($withTrashed) {
            $query->withTrashed();
        }
        
        return $query->find($id);
    }

    /**
     * Retorna o Eloquent FindOrFail
     */
    public function findOrFail(int $id, string|array $colums = 'id', bool $withTrashed = false): object
    {
        $query = $this->model
            ->select($colums);

        if ($withTrashed) {
            $query->withTrashed();
        }
        
        return $query->findOrFail($id);
    }

    /**
     * Retorna o Eloquent First
     */
    public function first(
        array $where,
        string|array $colums = 'id',
        ?array $orderBy = null,
        bool $withTrashed = false
    ): ?object {
        $query = $this->model
            ->select($colums);

        $this->scopeMakeWhere($query, $where);

        if ($orderBy) {
            foreach ($orderBy as $key => $value) {
                $query->orderBy($key, $value);
            }
        }
        
        if ($withTrashed) {
            $query->withTrashed();
        }
        
        return $query->first();
    }

    /**
     * Retorna o Eloquent Last
     */
    public function last(
        array $where,
        string|array $colums = 'id',
        ?array $orderBy = null,
        bool $withTrashed = false
    ): ?object {
        $query = $this->model
            ->select($colums);

        $this->scopeMakeWhere($query, $where);

        if ($orderBy) {
            foreach ($orderBy as $key => $value) {
                $query->orderBy($key, $value);
            }
        }
        
        if ($withTrashed) {
            $query->withTrashed();
        }
        
        return $query->latest()->first();
    }

    /**
     * Retorna o Eloquent FirstOrFail
     */
    public function firstOrFail(
        array $where,
        string|array $colums = 'id',
        ?array $orderBy = null,
        bool $withTrashed = false
    ): object {
        $query = $this->model
            ->select($colums);

        $this->scopeMakeWhere($query, $where);

        if ($orderBy) {
            foreach ($orderBy as $key => $value) {
                $query->orderBy($key, $value);
            }
        }
        
        if ($withTrashed) {
            $query->withTrashed();
        }
        
        return $query->firstOrFail();
    }

    /**
     * Retorna o Eloquent Get
     */
    public function get(
        string|array $where,
        string|array $colums = 'id',
        ?array $orderBy = null,
        ?bool $withTrashed = false
    ): object {
        $query = $this->model
            ->select($colums);

        $this->scopeMakeWhere($query, $where);

        if ($orderBy) {
            foreach ($orderBy as $key => $value) {
                $query->orderBy($key, $value);
            }
        }

        if ($withTrashed) {
            $query->withTrashed();
        }

        return $query->get();
    }

    /**
     * Retorna o Eloquent Get
     */
    public function count(array $where, ?int $limit = null): int
    {
        $query = $this->model
            ->select(1);

        if ($limit) {
            $query->limit($limit);
        }

        $this->scopeMakeWhere($query, $where);
        
        return $query->count();
    }

    /**
     * Insere um dado no banco
     */
    public function insert(array $data): ?object
    {
        $insert = new $this->model;

        if (empty($data)) {
            return null;
        }

        foreach ($data as $key => $value) {
            $insert->{$key} = $value;
        }

        return $insert->save() ? $insert : null;
    }

    /**
     * Insere múltiplos dados no banco
     */
    public function insertMultiples(array $data): bool
    {
        if (empty($data)) {
            return null;
        }

        return $this->model->insert($data);
    }

    /**
     * Captura o primeiro valor encontrado ou adiciona um novo
     * https://laravel.com/docs/8.x/eloquent#retrieving-or-creating-models
     *
     * @param array $where          Resgata o objeto por esse where, se não tiver, criar
     * @param array|null $fields    Adiciona os novos campos sem participar do where
     * @return object|null
     */
    public function firstOrCreate(array $where, ?array $fields = null): ?object
    {
        if (empty($where)) {
            return null;
        }
        
        if (empty($fields)) {
            return $this->model->firstOrCreate($where);
        }

        return $this->model->firstOrCreate($where, $fields);
    }

    /**
     * Atualiza um dado no banco
     *
     * @param object|integer $item  object (item) ou int (faz o find)
     * @param array $data           novos dados
     * @return object|null
     */
    public function update(object|int $item, array $data): ?object
    {
        $update = is_int($item) ? $this->find($item, '*') : $item;
        
        if (empty($update) || empty($data)) {
            return null;
        }

        foreach ($data as $key => $value) {
            $update->{$key} = $value;
        }

        return $update->save() ? $update : null;
    }

    /**
     * Atualiza múltiplos dados no banco
     *
     * @param object|array $where   where (array) ou itens (object)
     * @param array $data           novos dados
     * @return integer
     */
    public function updateMultiples(object|array $where, array $data): int
    {
        if (empty($where) || empty($data)) {
            return (int) 0;
        }

        $query = $this->model->select('*');

        $this->scopeMakeWhere($query, $where);

        return $query->update($data);
    }

    /**
     * Deleta dados no banco
     *
     * @param array $data
     * @return boolean
     */
    public function delete(array $data): bool
    {
        if (empty($data) || count($data) <= 0) {
            return false;
        }

        return $this->model->where($data)->delete();
    }

    /**
     * Realiza o where de maneira dinâmica, aceitando sinais e null
     *
     * @param Builder $query
     * @param array $where
     * @return Builder
     */
    protected function scopeMakeWhere(Builder $query, array $where): Builder
    {
        foreach ($where as $key => $value) {
            $keyExplode = explode(' ', $key);
            $condition = $keyExplode[1] ?? '=';

            if ($condition === '!=' && is_null($value)) {
                $query->whereNotNull($keyExplode[0]);
            } else if (is_null($value)) {
                $query->whereNull($keyExplode[0]);
            } else {
                $query->where($keyExplode[0], $condition, $value);
            }
        }

        return $query;
    }
}
