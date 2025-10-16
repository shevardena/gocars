<?php

namespace App\Interfaces;

use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

interface ExpenseRepositoryInterface
{
    public function getFieldSumByPeriod(Carbon $start, Carbon $end, string $field): float|null;

    public function filter(array $data): Builder;

    public function findByOperation(string $operation_id): Expense;

    public function getByOperation(string $operation_id): Collection;

    public function create(array $data): Expense;

    public function update(int $id, array $data): void;

    public function delete(int $id): void;
}
