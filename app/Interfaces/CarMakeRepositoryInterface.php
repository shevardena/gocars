<?php

namespace App\Interfaces;

use App\Models\CarMake;
use Illuminate\Database\Eloquent\Collection;

interface CarMakeRepositoryInterface
{
    public function getAll(): Collection;

    public function find($id): CarMake;

    public function create(array $data): CarMake;

    public function update(int $id, array $data): void;

    public function delete(int $id): void;
}
