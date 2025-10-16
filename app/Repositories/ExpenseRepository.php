<?php

namespace App\Repositories;

use App\Interfaces\ExpenseRepositoryInterface;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ExpenseRepository implements ExpenseRepositoryInterface
{
    protected Expense $model;

    public function __construct(Expense $expense)
    {
        $this->model = $expense;
    }

    public function getFieldSumByPeriod(Carbon $start, Carbon $end, string $field): float|null
    {
        return $this->model->whereBetween('created_at', [$start, $end])
            ->sum($field);
    }

    public function filter(array $data): Builder
    {

        $with = $data['with'] ?? null;
        $car_id = $data['car_id'] ?? null;
        $order_by = $data['order_by'] ?? null;
        $order_direction = $data['order_direction'] ?? null;
        $take = $data['take'] ?? null;

        return $this->model->when($with, function ($query, $with) {
            $query->with($with);
        })
            ->when($car_id, function ($query, $car_id) {
                return $query->where('car_id', $car_id);
            })
            ->when(!auth()->user()->super_admin, function ($query) {
                return $query->whereHas('backend_user', function ($query) {
                    return $query->where('super_admin', 0);
                });
            })->when($order_by, function ($query) use ($order_by, $order_direction) {
                $query->when($order_direction, function ($query) use ($order_by, $order_direction) {
                    $query->orderBy($order_by, $order_direction);
                })->when(!$order_direction, function ($query) use ($order_direction) {
                    $query->orderBy($order_direction);
                });
            })->when($take, function ($query, $take) {
                $query->take($take);
            });
    }

    public function findByOperation(string $operation_id): Expense
    {
        return $this->model->select('operation_id')
            ->selectRaw('SUM(amount_gel) as amount_gel')
            ->selectRaw('SUM(amount_usd) as amount_usd')
            ->selectRaw('MAX(title) as title')
            ->addSelect('car_id')
            ->where('operation_id', $operation_id)
            ->groupBy('operation_id', 'car_id')
            ->firstOrFail();
    }

    public function getByOperation(string $operation_id): Collection
    {
        return $this->model->where('operation_id', $operation_id)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function create(array $data): Expense
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
