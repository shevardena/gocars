<?php

namespace App\Repositories;

use App\Interfaces\CarMakeRepositoryInterface;
use App\Models\CarMake;
use Illuminate\Database\Eloquent\Collection;

class CarMakeRepository implements CarMakeRepositoryInterface
{
    protected CarMake $model;

    public function __construct(CarMake $carMake)
    {
        $this->model = $carMake;
    }

    public function getAll(): Collection
    {
        return $this->model->all();
    }

    public function find($id): CarMake
    {
        return $this->model->find($id);
    }

    public function create(array $data): CarMake
    {
        $this->model->create($data);
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
