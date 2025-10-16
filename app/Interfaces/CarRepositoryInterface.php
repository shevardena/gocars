<?php

namespace App\Interfaces;

use App\Models\Car;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface CarRepositoryInterface
{

    public function getLatest(): Collection;

    public function getAll(): Collection;

    public function getCountByPeriod(Carbon $start, Carbon $end): int;

    public function find(int $id, array $with = null): Car|null;

    public function findOrFail(int $id, array $with = null): Car;

    public function create(array $data): Car;

    public function update(int $id, array $data): void;

    public function delete(int $id): void;

    public function filterAndPaginate(
        ?string $orderBy = 'created_at',
        ?string $orderDirection = 'desc',
        ?int $perPage = 15,
        ?string $searchQuery = '',
    ): LengthAwarePaginator;

    public function getEmptyPaginatedData(): LengthAwarePaginator;
}
