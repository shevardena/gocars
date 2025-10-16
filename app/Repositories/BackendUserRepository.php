<?php

namespace App\Repositories;

use App\Interfaces\BackendUserRepositoryInterface;
use App\Models\BackendUser;
use Illuminate\Database\Eloquent\Collection;

class BackendUserRepository implements BackendUserRepositoryInterface
{
    protected BackendUser $model;

    public function __construct(BackendUser $backendUser)
    {
        $this->model = $backendUser;
    }

    public function getAll(): Collection
    {
        return $this->model->all();
    }

    public function find($id): BackendUser
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): void
    {
        $this->model->findOrFail($id)->update($data);
    }

    public function delete(int $id): void
    {
        $this->model->findOrFail($id)->delete();
    }
}
