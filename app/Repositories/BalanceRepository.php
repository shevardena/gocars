<?php

namespace App\Repositories;

use App\Interfaces\BalanceRepositoryInterface;
use App\Models\Balance;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class BalanceRepository implements BalanceRepositoryInterface
{
    protected Balance $model;


    public function getFieldSumByPeriod(Carbon $start, Carbon $end, string $field): float|null
    {
        return $this->model->whereBetween('created_at', [$start, $end])
            ->sum($field);
    }
    public function __construct(Balance $balance)
    {
        $this->model = $balance;
    }

    public function find(int $id): Balance
    {
        return $this->model->find($id);
    }

    public function getAll(): Collection
    {
        return $this->model->all();
    }

    public function getLatest(): Collection
    {
        return $this->model->orderByDesc('id')->take(5)->get();
    }

    public function getByUser(): Collection
    {
        return $this->model
            ->where('amount', '>', 0)
            ->where('backend_user_id', auth()->id())
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function create(array $data): Balance
    {
        return $this->model->create($data);
    }
}
