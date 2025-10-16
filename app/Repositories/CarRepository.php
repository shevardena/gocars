<?php

namespace App\Repositories;

use App\Interfaces\CarRepositoryInterface;
use App\Models\Car;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class CarRepository implements CarRepositoryInterface
{
    protected Car $model;

    public function __construct(Car $car)
    {
        $this->model = $car;
    }

    public function getLatest(): Collection
    {
        return $this->model->orderByDesc('id')->take(5)->get();
    }

    public function getAll(): Collection
    {
        return $this->model->all();
    }

    public function getCountByPeriod(Carbon $start, Carbon $end): int
    {
        return $this->model->whereBetween('created_at', [$start, $end])->count();
    }

    public function find($id, $with = null): Car|null
    {
        return $this->model->when($with, function ($query) use ($with) {
            return $query->with($with);
        })->find($id);
    }

    public function findOrFail($id, $with = null): Car
    {
        return $this->model->when($with, function ($query) use ($with) {
            return $query->with($with);
        })->findOrFail($id);
    }

    public function create(array $data): Car
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

    public function filterAndPaginate(
        ?string $orderBy = 'created_at',
        ?string $orderDirection = 'desc',
        ?int $perPage = 15,
        ?string $searchQuery = '',
    ): LengthAwarePaginator {
        return $this->model->with('images', 'model.make')
            ->when($searchQuery, function ($query) use ($searchQuery) {
                $query->where('year', 'like', "%{$searchQuery}%")
                    ->orWhere('vin', 'like', "%{$searchQuery}%")
                    ->orWhere('arrival_date', 'like', "%{$searchQuery}%")
                    ->orWhere('purchase_date', 'like', "%{$searchQuery}%")
                    ->orWhereHas('model', function ($q) use ($searchQuery) {
                        $q->where('name', 'like', "%{$searchQuery}%");
                    })
                    ->orWhereHas('model.make', function ($q) use ($searchQuery) {
                        $q->where('name', 'like', "%{$searchQuery}%");
                    });
            })
            ->orderBy($orderBy, $orderDirection)
            ->paginate($perPage);
    }

    public function getEmptyPAginatedData(): LengthAwarePaginator
    {
        return $this->model->where('created_at', 'not exists')->paginate(10);
    }
}
