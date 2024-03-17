<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface BaseRepositoryInterface
{
    public function findById(int|string $id, array $relations = [], array $columns = ['*']): ?Model;
    public function all(array $relations = [], array $columns = ['*']): Collection;
    public function create(array $attributes);
    public function update(Model $model, array $attributes);
    public function delete(Model $model):bool|null;
}
