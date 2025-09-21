<?php

namespace App\Interfaces;

use App\Models\Balance;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

interface BalanceRepositoryInterface
{

    public function getFieldSumByPeriod(Carbon $start, Carbon $end, string $field): float|null;
    public function find(int $id): Balance;

    public function getAll(): Collection;
    public function getLatest(): Collection;

    public function getByUser(): Collection;

    public function create(array $data): Balance;
}
