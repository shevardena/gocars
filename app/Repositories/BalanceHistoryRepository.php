<?php

namespace App\Repositories;

use App\Interfaces\BalanceHistoryRepositoryInterface;
use App\Models\BalanceHistory;

class BalanceHistoryRepository implements BalanceHistoryRepositoryInterface
{

    protected BalanceHistory $model;

    public function __construct(BalanceHistory $balanceHistory)
    {
        $this->model = $balanceHistory;
    }

    public function create(array $data): void
    {
        $this->model->create($data);
    }
}
