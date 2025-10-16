<?php

namespace App\Interfaces;

use App\Models\BackendUser;
use Illuminate\Database\Eloquent\Collection;

interface BackendUserRepositoryInterface
{
    public function getAll(): Collection;

    public function find($id): BackendUser;

    public function create(array $data);

    public function update(int $id, array $data);

    public function delete(int $id);
}
