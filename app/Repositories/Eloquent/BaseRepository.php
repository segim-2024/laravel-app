<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function findById(int|string $id, array $relations = [], array $columns = ['*']): ?Model
    {
        return $this->model->with($relations)->find($id, $columns);
    }

    public function all(array $relations = [], array $columns = ['*']): Collection
    {
        return $this->model->with($relations)->get($columns);
    }

    public function create(array $attributes)
    {
        return $this->model->create($attributes);
    }

    public function update(Model $model, array $attributes)
    {
        $model->fill($attributes)->save();
        return $model;
    }

    public function delete(Model $model):bool|null
    {
        return $model->delete();
    }
}
