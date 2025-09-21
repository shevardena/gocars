<?php

namespace App\Services;

use App\Interfaces\BalanceHistoryRepositoryInterface;
use App\Models\Balance;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class BalanceHistoryService
{

    protected BalanceHistoryRepositoryInterface $balanceHistoryRepository;

    public function __construct(BalanceHistoryRepositoryInterface $balanceHistoryRepository)
    {
        $this->balanceHistoryRepository = $balanceHistoryRepository;
    }

    /**
     * @param Balance|Expense|array $data
     * @param string $operation_type
     * @return void
     * @throws \Exception
     */
    public function     create(Balance|Expense|array $data, string $operation_type): void
    {
        DB::beginTransaction();
        try {
            $data = $this->prepareData($data, $operation_type);
            $this->balanceHistoryRepository->create($data);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
        DB::commit();
    }

    /**
     * Prepare data
     * @param Balance|Expense|array $data
     * @param string $operation_type
     * @return array
     */
    public function prepareData(Balance|Expense|array $data, string $operation_type): array
    {
        if (is_array($data)) {
            $usd_rate = $data['usd_rate'] ?? null;
            $amount_usd = $data['amount_usd'] ?? null;
            $amount_gel = $data['amount_gel'] ?? null;
            $backend_user_id = $data['backend_user_id'] ?? null;
            $balance_id = $data['balance_id'] ?? $data['id'] ?? null;
        } else {
            $usd_rate = ($data instanceof Balance) ? $data->usd_rate : ($data->balance->usd_rate ?? null);
            $amount_usd = $data->amount_usd ?? null;
            $amount_gel = $data->amount_gel ?? null;
            $backend_user_id = $data->backend_user_id ?? null;
            $balance_id = $data->balance_id ?? $data->id;
        }

        return [
            'operation_type' => $operation_type,
            'amount_usd' => $amount_usd,
            'amount_gel' => $amount_gel,
            'usd_rate' => $usd_rate,
            'author_id' => auth()->id(),
            'backend_user_id' => $backend_user_id,
            'balance_id' => $balance_id,
        ];
    }

}
